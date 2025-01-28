<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CutiPerizinan;
use Illuminate\Http\Request;

class CutiPerizinanController extends Controller
{
    public function index()
    {
        $cutiPerizinans = CutiPerizinan::with(['user', 'disetujuiOleh'])->get();
        return response()->json($cutiPerizinans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
            'status_pengajuan' => 'required|in:diajukan,disetujui,ditolak',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'surat_izin' => 'nullable|string',
        ]);

        $cutiPerizinan = CutiPerizinan::create($request->all());

        return response()->json($cutiPerizinan, 201);
    }

    public function show(CutiPerizinan $cutiPerizinan)
    {
        return response()->json($cutiPerizinan->load(['user', 'disetujuiOleh']));
    }

    public function update(Request $request, CutiPerizinan $cutiPerizinan)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
            'status_pengajuan' => 'required|in:diajukan,disetujui,ditolak',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'surat_izin' => 'nullable|string',
        ]);

        $cutiPerizinan->update($request->all());

        return response()->json($cutiPerizinan);
    }

    public function destroy(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->delete();
        return response()->json(null, 204);
    }

    public function approve(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->update(['status_pengajuan' => 'disetujui', 'disetujui_oleh' => auth()->id()]);
        return response()->json($cutiPerizinan);
    }

    public function reject(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->update(['status_pengajuan' => 'ditolak', 'disetujui_oleh' => auth()->id()]);
        return response()->json($cutiPerizinan);
    }
}