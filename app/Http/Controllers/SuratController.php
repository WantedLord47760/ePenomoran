<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\TipeSurat;
use App\Services\LetterNumberingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SuratController extends Controller
{
    use AuthorizesRequests;

    protected $letterNumberingService;

    public function __construct(LetterNumberingService $letterNumberingService)
    {
        $this->letterNumberingService = $letterNumberingService;
    }

    /**
     * Display a listing of surats with search, filter, and sorting
     */
    public function index(Request $request)
    {
        $query = Surat::with(['user', 'tipeSurat']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat_full', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tipe surat
        if ($request->filled('tipe_surat_id')) {
            $query->where('tipe_surat_id', $request->tipe_surat_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_surat', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_surat', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['created_at', 'tanggal_surat', 'nomor_surat_full', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $surats = $query->paginate(10)->withQueryString();
        $tipeSurats = TipeSurat::all();

        return view('surat.index', compact('surats', 'tipeSurats'));
    }

    public function create()
    {
        $this->authorize('create', Surat::class);

        $tipeSurats = TipeSurat::all();
        return view('surat.create', compact('tipeSurats'));
    }

    /**
     * Store a newly created surat with race-condition protection.
     * 
     * WHY THIS IS RACE-SAFE:
     * 1. lockForUpdate() acquires database row lock - forces sequential execution
     * 2. Transaction isolation prevents phantom reads
     * 3. Yearly reset check happens INSIDE the lock
     * 4. Database unique constraint on nom or_surat_full is final defense
     * 5. Audit log captures all actions for forensics
     * 
     * CONCURRENCY BEHAVIOR:
     * - Request A: Acquires lock on tipe_surat row
     * - Request B: Waits (blocks) until  A commits
     * - Request C: Waits until B commits
     * - Result: Sequential number generation guaranteed
     */
    public function store(Request $request)
    {
        // Authorization check via policy
        $this->authorize('create', Surat::class);

        $validated = $request->validate([
            'tipe_surat_id' => 'required|exists:tipe_surats,id',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // CRITICAL: Acquire exclusive lock on tipe_surat row
            // This prevents race conditions by forcing sequential access
            $tipeSurat = TipeSurat::where('id', $validated['tipe_surat_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $tanggalSurat = \Carbon\Carbon::parse($validated['tanggal_surat']);
            $currentYear = $tanggalSurat->year;

            // YEARLY RESET LOGIC
            // Check if year has changed since last letter generation
            if ($tipeSurat->last_reset_year !== null && $tipeSurat->last_reset_year != $currentYear) {
                // New year detected - reset counter to 0
                $tipeSurat->nomor_terakhir = 0;
                $tipeSurat->last_reset_year = $currentYear;
                $tipeSurat->save();
            } elseif ($tipeSurat->last_reset_year === null) {
                // First letter ever for this type - initialize year
                $tipeSurat->last_reset_year = $currentYear;
                $tipeSurat->save();
            }

            // Generate letter number
            $letterData = $this->letterNumberingService->generateLetterNumber(
                $tipeSurat,
                $tanggalSurat
            );

            // Create surat
            $surat = Surat::create([
                'user_id' => Auth::id(),
                'tipe_surat_id' => $validated['tipe_surat_id'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tujuan' => $validated['tujuan'],
                'perihal' => $validated['perihal'],
                'nomor_urut' => $letterData['nomor_urut'],
                'nomor_surat_full' => $letterData['nomor_surat_full'],
                'status' => '0',
            ]);

            // Increment counter (still inside lock)
            $tipeSurat->increment('nomor_terakhir');

            // Create audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'created',
                null,
                '0',
                'Letter created with number: ' . $letterData['nomor_surat_full']
            );

            DB::commit();

            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil dibuat dengan nomor: ' . $letterData['nomor_surat_full']);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Check if unique constraint violation (duplicate number)
            if ($e->getCode() == 23000) {
                \Log::error('Duplicate letter number detected: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Nomor surat duplikat terdeteksi. Silakan coba lagi.'])
                    ->withInput();
            }

            \Log::error('Database error creating surat: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan database. Hubungi administrator.'])
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating surat: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal membuat surat. Silakan coba lagi.'])
                ->withInput();
        }
    }

    public function show(Surat $surat)
    {
        $surat->load(['user', 'tipeSurat']);
        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        // Only admin and operator can edit
        if (!in_array(Auth::user()->role, ['admin', 'operator'])) {
            abort(403, 'Unauthorized action.');
        }

        $tipeSurats = TipeSurat::all();
        return view('surat.edit', compact('surat', 'tipeSurats'));
    }

    public function update(Request $request, Surat $surat)
    {
        $this->authorize('update', $surat);

        $validated = $request->validate([
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $surat->update($validated);

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'updated',
                null,
                null,
                "Updated tujuan/perihal"
            );

            DB::commit();
            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating surat: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui surat.');
        }
    }

    public function destroy(Surat $surat)
    {
        $this->authorize('delete', $surat);

        DB::beginTransaction();
        try {
            // Track who deleted it
            $surat->deleted_by = Auth::id();
            $surat->save();

            // Soft delete
            $surat->delete();

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'deleted',
                $surat->status,
                null,
                'Letter soft-deleted'
            );

            DB::commit();
            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting surat: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus surat.');
        }
    }

    public function approve(Surat $surat)
    {
        $this->authorize('approve', $surat);

        DB::beginTransaction();
        try {
            $oldStatus = $surat->status;

            $surat->update([
                'status' => '1',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'approved',
                $oldStatus,
                '1',
                'Letter approved by ' . Auth::user()->name
            );

            DB::commit();
            return back()->with('success', 'Surat berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approval failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyetujui surat.');
        }
    }

    public function reject(Request $request, Surat $surat)
    {
        $this->authorize('reject', $surat);

        DB::beginTransaction();
        try {
            $oldStatus = $surat->status;

            $surat->update([
                'status' => '2',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->input('reason', 'No reason provided'),
            ]);

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'rejected',
                $oldStatus,
                '2',
                'Letter rejected: ' . $request->input('reason', 'No reason')
            );

            DB::commit();
            return back()->with('success', 'Surat berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Rejection failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menolak surat.');
        }
    }

    public function print(Surat $surat)
    {
        $this->authorize('print', $surat);

        $surat->load(['user', 'tipeSurat', 'approver']);
        return view('surat.cetak', compact('surat'));
    }
}
