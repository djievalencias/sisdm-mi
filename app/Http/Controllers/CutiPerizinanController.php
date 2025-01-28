<?php

namespace App\Http\Controllers;

use App\Models\CutiPerizinan;
use App\Models\User;
use Illuminate\Http\Request;

class CutiPerizinanController extends Controller
{
    public function index()
    {
        $cutiPerizinans = CutiPerizinan::with(['user', 'disetujuiOleh'])->get();
        return view('cuti_perizinan.index', compact('cutiPerizinans'));
    }

    public function create()
    {
        $users = User::all();
        return view('cuti_perizinan.create', compact('users'));
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

        CutiPerizinan::create($request->all());

        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function show(CutiPerizinan $cutiPerizinan)
    {
        return view('cuti_perizinan.show', compact('cutiPerizinan'));
    }

    public function edit(CutiPerizinan $cutiPerizinan)
    {
        $users = User::all();
        return view('cuti_perizinan.edit', compact('cutiPerizinan', 'users'));
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

        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil diperbarui.');
    }

    public function destroy(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->delete();
        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil dihapus.');
    }

    public function approve(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->update(['status_pengajuan' => 'disetujui', 'disetujui_oleh' => auth()->id()]);
        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil disetujui.');
    }

    public function reject(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->update(['status_pengajuan' => 'ditolak', 'disetujui_oleh' => auth()->id()]);
        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil ditolak.');
    }
}