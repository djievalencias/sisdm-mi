<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\DistribusiPengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $pengumuman = Pengumuman::select('id', 'judul', 'pesan', 'foto', 'created_by', 'updated_by', 'created_at')
            ->with([
                'distribusi:id_pengumuman,id_departemen', 
                'creator:id,nama', 
                'updater:id,nama'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $pengumuman,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePengumuman($request);
        $validated['created_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengumuman_foto', 'public');
        }

        $pengumuman = Pengumuman::create($validated);

        // Bulk insert distribusi records for efficiency
        $distribusiData = array_map(fn($id_departemen) => [
            'id_pengumuman' => $pengumuman->id,
            'id_departemen' => $id_departemen,
        ], $validated['departemen']);

        DistribusiPengumuman::insert($distribusiData);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dibuat.',
            'data' => $pengumuman->load(['distribusi:id_pengumuman,id_departemen']),
        ]);
    }

    /**
     * Get a single pengumuman by ID.
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load([
            'distribusi:id_pengumuman,id_departemen', 
            'creator:id,nama', 
            'updater:id,nama'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $pengumuman,
        ]);
    }

    /**
     * Update an existing pengumuman.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $this->validatePengumuman($request);
        $validated['updated_by'] = auth()->id();

        // Handle image update with file deletion
        if ($request->hasFile('foto')) {
            if ($pengumuman->foto) {
                Storage::disk('public')->delete($pengumuman->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pengumuman_foto', 'public');
        }

        $pengumuman->update($validated);

        // Update distribusi efficiently
        $existingDepartemen = DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)
            ->pluck('id_departemen')
            ->toArray();

        $newDepartemen = array_diff($validated['departemen'], $existingDepartemen);
        $deletedDepartemen = array_diff($existingDepartemen, $validated['departemen']);

        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)
            ->whereIn('id_departemen', $deletedDepartemen)
            ->delete();

        $distribusiData = array_map(fn($id_departemen) => [
            'id_pengumuman' => $pengumuman->id,
            'id_departemen' => $id_departemen,
        ], $newDepartemen);

        DistribusiPengumuman::insert($distribusiData);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil diperbarui.',
            'data' => $pengumuman->load(['distribusi:id_pengumuman,id_departemen']),
        ]);
    }

    /**
     * Delete a pengumuman and its distribusi.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->foto) {
            Storage::disk('public')->delete($pengumuman->foto);
        }

        DistribusiPengumuman::where('id_pengumuman', $pengumuman->id)->delete();
        $pengumuman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }

    /**
     * Validate Pengumuman request.
     */
    private function validatePengumuman(Request $request)
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required',
            'foto' => 'nullable|image|max:2048',
            'departemen' => 'required|array',
            'departemen.*' => 'exists:departemen,id',
        ]);
    }
}
