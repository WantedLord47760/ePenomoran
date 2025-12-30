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

        // Pegawai can only see their own letters
        if (Auth::user()->role === 'pegawai') {
            $query->where('user_id', Auth::id());
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
            'isi_surat' => 'nullable|string',
            'metode_pembuatan' => 'required|in:Srikandi,TTE,Manual',
            'file_surat' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Handle file upload
            $filePath = null;
            $originalName = null;
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('surat-files', 'public');
            }

            // Create surat WITHOUT number - number will be assigned on approval
            $surat = Surat::create([
                'user_id' => Auth::id(),
                'tipe_surat_id' => $validated['tipe_surat_id'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tujuan' => $validated['tujuan'],
                'perihal' => $validated['perihal'],
                'isi_surat' => $validated['isi_surat'] ?? null,
                'metode_pembuatan' => $validated['metode_pembuatan'],
                'file_surat' => $filePath,
                'file_surat_original_name' => $originalName,
                'nomor_urut' => null, // No number on creation
                'nomor_surat_full' => null, // No number on creation
                'status' => '0', // Pending
            ]);

            // Create audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'created',
                null,
                '0',
                'Letter created (pending approval for numbering)'
            );

            DB::commit();

            return redirect()->route('surat.index')
                ->with('success', 'Surat berhasil dibuat. Menunggu persetujuan untuk penomoran.');

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
        $this->authorize('update', $surat);

        $tipeSurats = TipeSurat::all();
        return view('surat.edit', compact('surat', 'tipeSurats'));
    }

    public function update(Request $request, Surat $surat)
    {
        $this->authorize('update', $surat);

        // Different validation rules based on user role and surat status
        $rules = [
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
            'isi_surat' => 'nullable|string',
            'file_surat' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ];

        // Only require nomor_surat_full for admin/operator on approved letters
        if ($surat->nomor_surat_full && in_array(Auth::user()->role, ['admin', 'operator'])) {
            $rules['nomor_surat_full'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Handle file upload
            if ($request->hasFile('file_surat')) {
                // Delete old file if exists
                if ($surat->file_surat) {
                    \Storage::disk('public')->delete($surat->file_surat);
                }

                $file = $request->file('file_surat');
                $originalName = $file->getClientOriginalName();
                $filePath = $file->store('surat-files', 'public');

                $surat->file_surat = $filePath;
                $surat->file_surat_original_name = $originalName;
            }

            $oldNomor = $surat->nomor_surat_full;

            // Update fields
            if (isset($validated['nomor_surat_full'])) {
                $surat->nomor_surat_full = $validated['nomor_surat_full'];
            }
            $surat->tujuan = $validated['tujuan'];
            $surat->perihal = $validated['perihal'];
            $surat->isi_surat = $validated['isi_surat'] ?? $surat->isi_surat;

            // Handle resubmit from pegawai
            if ($request->input('resubmit') && $surat->status == '2') {
                $surat->status = '0'; // Set back to pending
                $surat->rejection_reason = null;
                $surat->rejected_at = null;
                $surat->rejected_by = null;
            }

            $surat->save();

            // Audit log
            $logMessage = "Updated surat";
            if ($request->input('resubmit')) {
                $logMessage = "Resubmitted surat after rejection";
            } elseif ($oldNomor !== ($validated['nomor_surat_full'] ?? $oldNomor)) {
                $logMessage .= " (nomor changed from {$oldNomor} to {$validated['nomor_surat_full']})";
            }
            \App\Models\SuratAuditLog::log(
                $surat->id,
                $request->input('resubmit') ? 'resubmitted' : 'updated',
                null,
                null,
                $logMessage
            );

            DB::commit();

            $successMessage = $request->input('resubmit')
                ? 'Surat berhasil diajukan ulang dan menunggu persetujuan.'
                : 'Surat berhasil diperbarui.';

            return redirect()->route('surat.index')
                ->with('success', $successMessage);

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
            // Store info for resequencing before deletion
            $tipeSuratId = $surat->tipe_surat_id;
            $year = \Carbon\Carbon::parse($surat->tanggal_surat)->year;

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

            // Resequence remaining letters of same type and year
            $resequencedCount = $this->letterNumberingService->resequenceLetterNumbers(
                $tipeSuratId,
                $year
            );

            if ($resequencedCount > 0) {
                \App\Models\SuratAuditLog::log(
                    $surat->id,
                    'resequence_triggered',
                    null,
                    null,
                    "Deletion triggered resequencing of {$resequencedCount} letters"
                );
            }

            DB::commit();
            $message = 'Surat berhasil dihapus.';
            if ($resequencedCount > 0) {
                $message .= " {$resequencedCount} surat lain telah dinomori ulang.";
            }
            return redirect()->route('surat.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting surat: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus surat.');
        }
    }

    /**
     * Show approve confirmation page
     */
    public function showApprove(Surat $surat)
    {
        $this->authorize('approve', $surat);

        $surat->load(['user', 'tipeSurat']);

        return view('surat.approve', compact('surat'));
    }

    /**
     * Show reject form page
     */
    public function showReject(Surat $surat)
    {
        $this->authorize('reject', $surat);

        $surat->load(['user', 'tipeSurat']);

        return view('surat.reject', compact('surat'));
    }

    public function approve(Surat $surat)
    {
        $this->authorize('approve', $surat);

        DB::beginTransaction();
        try {
            $oldStatus = $surat->status;

            // Load tipe surat with lock for number generation
            $tipeSurat = TipeSurat::where('id', $surat->tipe_surat_id)
                ->lockForUpdate()
                ->firstOrFail();

            $tanggalSurat = \Carbon\Carbon::parse($surat->tanggal_surat);
            $currentYear = $tanggalSurat->year;

            // YEARLY RESET LOGIC
            if ($tipeSurat->last_reset_year !== null && $tipeSurat->last_reset_year != $currentYear) {
                $tipeSurat->nomor_terakhir = 0;
                $tipeSurat->last_reset_year = $currentYear;
                $tipeSurat->save();
            } elseif ($tipeSurat->last_reset_year === null) {
                $tipeSurat->last_reset_year = $currentYear;
                $tipeSurat->save();
            }

            // Generate letter number NOW (on approval)
            $letterData = $this->letterNumberingService->generateLetterNumber(
                $tipeSurat,
                $tanggalSurat
            );

            // Update surat with number and approval
            $surat->update([
                'nomor_urut' => $letterData['nomor_urut'],
                'nomor_surat_full' => $letterData['nomor_surat_full'],
                'status' => '1',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Increment counter
            $tipeSurat->increment('nomor_terakhir');

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'approved',
                $oldStatus,
                '1',
                'Letter approved and numbered: ' . $letterData['nomor_surat_full'] . ' by ' . Auth::user()->name
            );

            DB::commit();
            return redirect()->route('surat.index')->with('success', 'Surat berhasil disetujui dengan nomor: ' . $letterData['nomor_surat_full']);

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
            return redirect()->route('surat.index')->with('success', 'Surat berhasil ditolak.');

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

    /**
     * Download the uploaded letter file
     */
    public function download(Surat $surat)
    {
        if (!$surat->file_surat) {
            return back()->with('error', 'File surat tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $surat->file_surat);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File surat tidak ditemukan di server.');
        }

        return response()->download(
            $filePath,
            $surat->file_surat_original_name ?? basename($surat->file_surat)
        );
    }

    /**
     * Resubmit a rejected letter (changes status back to pending)
     */
    public function resubmit(Surat $surat)
    {
        $this->authorize('resubmit', $surat);

        DB::beginTransaction();
        try {
            $oldStatus = $surat->status;

            // Reset to pending status
            $surat->update([
                'status' => '0',
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null,
            ]);

            // Audit log
            \App\Models\SuratAuditLog::log(
                $surat->id,
                'resubmitted',
                $oldStatus,
                '0',
                'Letter resubmitted for review by ' . Auth::user()->name
            );

            DB::commit();
            return back()->with('success', 'Surat berhasil diajukan ulang untuk peninjauan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Resubmit failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengajukan ulang surat.');
        }
    }
}

