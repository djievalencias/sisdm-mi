<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\DistribusiPengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with(['distribusi', 'creator', 'updater'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $pengumuman,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'foto' => 'nullable|image|max:2048',
            'departemen' => 'required|array',
            'departemen.*' => 'exists:departemen,id',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman_foto', 'public');
        }

        $pengumuman = Pengumuman::create($validated);

        foreach ($validated['departemen'] as $id_departemen) {
            DistribusiPengumuman::create([
                'id_pengumuman' => $pengumuman->id,
                'id_departemen' => $id_departemen,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dibuat.',
            'data' => $pengumuman,
        ]);
    }

    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load(['distribusi', 'creator', 'updater']);

        return response()->json([
            'status' => 'success',
            'data' => $pengumuman,
        ]);
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

        $pengumuman->update($validated);

        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)->delete();
        foreach ($validated['departemen'] as $id_departemen) {
            DistribusiPengumuman::create([
                'id_pengumuman' => $pengumuman->id,
                'id_departemen' => $id_departemen,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil diperbarui.',
            'data' => $pengumuman,
        ]);
    }

    public function destroy(Pengumuman $pengumuman)
    {
        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)->delete();

        $pengumuman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }
}
