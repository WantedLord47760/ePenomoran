<?php

namespace App\Http\Controllers;

use App\Models\TipeSurat;
use Illuminate\Http\Request;

class TipeSuratController extends Controller
{
    public function index()
    {
        $tipeSurats = TipeSurat::withCount('surats')->get();
        return view('tipe-surat.index', compact('tipeSurats'));
    }

    public function create()
    {
        return view('tipe-surat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'format_penomoran' => 'required|string|max:255',
        ]);

        $validated['nomor_terakhir'] = 0;

        TipeSurat::create($validated);

        return redirect()->route('tipe-surat.index')
            ->with('success', 'Tipe surat berhasil ditambahkan.');
    }

    public function show(TipeSurat $tipeSurat)
    {
        return view('tipe-surat.show', compact('tipeSurat'));
    }

    public function edit(TipeSurat $tipeSurat)
    {
        return view('tipe-surat.edit', compact('tipeSurat'));
    }

    public function update(Request $request, TipeSurat $tipeSurat)
    {
        $validated = $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'format_penomoran' => 'required|string|max:255',
        ]);

        $tipeSurat->update($validated);

        return redirect()->route('tipe-surat.index')
            ->with('success', 'Tipe surat berhasil diperbarui.');
    }

    public function destroy(TipeSurat $tipeSurat)
    {
        $tipeSurat->delete();

        return redirect()->route('tipe-surat.index')
            ->with('success', 'Tipe surat berhasil dihapus.');
    }
}
