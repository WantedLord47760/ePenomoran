<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /**
     * Display a listing of users (pegawai management)
     */
    public function index(Request $request)
    {
        // Only admin, pemimpin, operator can access
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by bidang
        if ($request->filled('bidang')) {
            $query->where('bidang', $request->bidang);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('pegawai.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $bidangOptions = User::getBidangOptions();
        return view('pegawai.create', compact('bidangOptions'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,pemimpin,pegawai',
            'nip' => 'nullable|string|max:20|unique:users,nip',
            'no_hp' => 'nullable|string|max:15',
            'jabatan' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'bidang' => 'nullable|in:Sekretariat,Bidang TIK dan Persandian,Bidang IKPS,Bidang Aptika',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nip' => $validated['nip'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'pangkat' => $validated['pangkat'] ?? null,
            'bidang' => $validated['bidang'] ?? null,
        ]);

        return redirect()->route('pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $pegawai)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $bidangOptions = User::getBidangOptions();
        return view('pegawai.edit', compact('pegawai', 'bidangOptions'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $pegawai)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($pegawai->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,operator,pemimpin,pegawai',
            'nip' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($pegawai->id)],
            'no_hp' => 'nullable|string|max:15',
            'jabatan' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'bidang' => 'nullable|in:Sekretariat,Bidang TIK dan Persandian,Bidang IKPS,Bidang Aptika',
        ]);

        $pegawai->name = $validated['name'];
        $pegawai->email = $validated['email'];
        $pegawai->role = $validated['role'];
        $pegawai->nip = $validated['nip'] ?? null;
        $pegawai->no_hp = $validated['no_hp'] ?? null;
        $pegawai->jabatan = $validated['jabatan'] ?? null;
        $pegawai->pangkat = $validated['pangkat'] ?? null;
        $pegawai->bidang = $validated['bidang'] ?? null;

        if (!empty($validated['password'])) {
            $pegawai->password = Hash::make($validated['password']);
        }

        $pegawai->save();

        return redirect()->route('pegawai.index')
            ->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $pegawai)
    {
        // Only admin can delete
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Prevent self-deletion
        if ($pegawai->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
