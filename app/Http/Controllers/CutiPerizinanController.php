<?php

namespace App\Http\Controllers;

use App\Models\CutiPerizinan;
use App\Models\User;
use Illuminate\Http\Request;

class CutiPerizinanController extends Controller
{
    public function index()
    {
        $cutiPerizinans = CutiPerizinan::with('user')->get(['id', 'id_user', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'status_pengajuan']);
        return view('pages.cuti_perizinan.index', compact('cutiPerizinans'));
    }

    public function edit(CutiPerizinan $cutiPerizinan)
    {
        $users = User::all();
        return view('pages.cuti_perizinan.edit', compact('cutiPerizinan', 'users'));
    }

    public function update(Request $request, CutiPerizinan $cutiPerizinan)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
        ]);

        $cutiPerizinan->update($request->all());
        return redirect()->route('cuti-perizinan.index')->with('success', 'Data permohonan izin berhasil diperbarui.');
    }

    public function approve(CutiPerizinan $cutiPerizinan)
{
    $cutiPerizinan->update([
        'status_pengajuan' => 'disetujui',
        'disetujui_oleh' => auth()->id() // Pastikan user sedang login
    ]);

    return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan telah disetujui.');
}

public function reject(CutiPerizinan $cutiPerizinan)
{
    $cutiPerizinan->update([
        'status_pengajuan' => 'ditolak',
        'disetujui_oleh' => auth()->id()
    ]);

    return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan telah ditolak.');
}


    public function hasilPermohonan(Request $request)
    {
        // Filter hanya permohonan yang disetujui atau ditolak
        $status = $request->input('status');

        $query = CutiPerizinan::with('user')
            ->whereIn('status_pengajuan', ['disetujui', 'ditolak']);

        if ($status) {
            $query->where('status_pengajuan', $status);
        }

        $cutiPerizinans = $query->orderBy('updated_at', 'desc')->get();

        return view('pages.cuti_perizinan.hasil', compact('cutiPerizinans', 'status'));
    }

    public function undoApproval(CutiPerizinan $cutiPerizinan)
    {
        // Update status menjadi "diajukan" kembali
        $cutiPerizinan->update([
            'status_pengajuan' => 'diajukan',
            'disetujui_oleh' => null
        ]);

        return redirect()->route('cuti-perizinan.hasil')->with('success', 'Status permohonan berhasil dikembalikan.');
    }



    public function destroy(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->delete();
        return redirect()->route('cuti-perizinan.index')->with('success', 'Permohonan izin berhasil dihapus.');
    }

    public function show(CutiPerizinan $cutiPerizinan)
    {
        return view('pages.cuti_perizinan.show', compact('cutiPerizinan'));
    }
}
