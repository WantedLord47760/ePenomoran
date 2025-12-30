<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratMasukController extends Controller
{
    /**
     * Check if user has access to surat masuk
     */
    private function checkAccess()
    {
        if (!in_array(Auth::user()->role, ['admin', 'admin_surat_masuk', 'pemimpin'])) {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display a listing of surat masuk
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = SuratMasuk::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('judul_surat', 'like', "%{$search}%")
                    ->orWhere('jenis_surat', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_tindak_lanjut', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_surat', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_surat', '<=', $request->date_to);
        }

        $suratMasuks = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('surat-masuk.index', compact('suratMasuks'));
    }

    /**
     * Show the form for creating a new surat masuk
     */
    public function create()
    {
        $this->checkAccess();
        return view('surat-masuk.create');
    }

    /**
     * Store a newly created surat masuk
     */
    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'jenis_surat' => 'required|string|max:255',
            'judul_surat' => 'required|string|max:255',
            'isi_surat' => 'nullable|string',
            'disposisi_pimpinan' => 'nullable|string',
            'tanggal_disposisi' => 'nullable|date',
            'status_tindak_lanjut' => 'required|in:pending,proses,selesai',
            'posisi_tindak_lanjut' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified surat masuk
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $this->checkAccess();
        $suratMasuk->load('user');
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    /**
     * Show the form for editing the specified surat masuk
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        $this->checkAccess();
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    /**
     * Update the specified surat masuk
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'jenis_surat' => 'required|string|max:255',
            'judul_surat' => 'required|string|max:255',
            'isi_surat' => 'nullable|string',
            'disposisi_pimpinan' => 'nullable|string',
            'tanggal_disposisi' => 'nullable|date',
            'status_tindak_lanjut' => 'required|in:pending,proses,selesai',
            'posisi_tindak_lanjut' => 'nullable|string|max:255',
        ]);

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified surat masuk
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        $this->checkAccess();

        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil dihapus.');
    }
}
