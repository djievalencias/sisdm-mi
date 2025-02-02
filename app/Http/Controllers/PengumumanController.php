<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\DistribusiPengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        // Ambil data pengumuman dengan distribusi dan creator
        $pengumuman = Pengumuman::with(['distribusi', 'creator', 'updater'])->get();
        return view('pages.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        // Load semua departemen untuk pilihan distribusi
        $departemen = \App\Models\Departemen::all();
        return view('pages.pengumuman.create', compact('departemen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'foto' => 'nullable|image|max:2048',
            'departemen' => 'required|array', // Departemen harus berupa array
            'departemen.*' => 'exists:departemen,id', // Setiap ID harus ada di tabel departemen
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman_foto', 'public');
        }

        // Buat pengumuman baru
        $pengumuman = Pengumuman::create($validated);

        // Tambahkan distribusi ke departemen yang dipilih
        foreach ($validated['departemen'] as $id_departemen) {
            DistribusiPengumuman::create([
                'id_pengumuman' => $pengumuman->id,
                'id_departemen' => $id_departemen,
            ]);
        }

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        // Load semua departemen dan distribusi terkait pengumuman
        $departemen = \App\Models\Departemen::all();
        $distribusi = $pengumuman->distribusi->pluck('id_departemen')->toArray();

        return view('pages.pengumuman.edit', compact('pengumuman', 'departemen', 'distribusi'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'foto' => 'nullable|image|max:2048',
            'departemen' => 'required|array',
            'departemen.*' => 'exists:departemen,id',
        ]);

        $validated['updated_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman_foto', 'public');
        }

        // Update pengumuman
        $pengumuman->update($validated);

        // Update distribusi pengumuman
        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)->delete();
        foreach ($validated['departemen'] as $id_departemen) {
            DistribusiPengumuman::create([
                'id_pengumuman' => $pengumuman->id,
                'id_departemen' => $id_departemen,
            ]);
        }

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        // Hapus distribusi terkait
        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)->delete();

        // Hapus pengumuman
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
