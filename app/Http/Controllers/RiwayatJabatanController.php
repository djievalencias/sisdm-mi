<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\Models\RiwayatJabatan;
use App\Models\User;

class RiwayatJabatanController extends Controller
{
    public function create($user_id)
    {
        $user = User::findOrFail($user_id);
        $jabatanList = Jabatan::with(['grup.departemen.kantor'])->get(); 

        return view('pages.riwayat_jabatan.create', compact('user', 'jabatanList'));
    }


    public function store(Request $request, $user_id)
    {
        $validatedData = $this->validateData($request);
        $validatedData['id_user'] = $user_id;

        $existingActiveJabatan = RiwayatJabatan::where('id_user', $user_id)
        ->whereNull('tanggal_selesai')
        ->exists();

        if ($existingActiveJabatan && $request->tanggal_selesai == null) {
            return redirect()->back()->withErrors(['error' => 'Karyawan sudah memiliki jabatan yang aktif. Harap berikan tanggal berakhirnya jabatan saat ini sebelum menambahkan jabatan baru.']);
        }

        RiwayatJabatan::create($validatedData);

        return redirect()->route('user.edit', $user_id)->with('status', 'Riwayat Jabatan added successfully!');
    }

    public function edit($user_id, $id)
    {
        $user = User::findOrFail($user_id);
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);
        $jabatanList = Jabatan::with(['grup.departemen.kantor'])->get();

        return view('pages.riwayat_jabatan.edit', compact('user', 'riwayatJabatan','jabatanList'));
    }

    public function update(Request $request, $user_id, $id)
    {
        $validatedData = $this->validateData($request);
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);

        $existingActiveJabatan = RiwayatJabatan::where('id_user', $user_id)
        ->whereNull('tanggal_selesai')
        ->where('id', '!=', $id)
        ->exists();

        if ($existingActiveJabatan && $request->tanggal_selesai == null) {
            return redirect()->back()->withErrors(['error' => 'Karyawan sudah memiliki jabatan yang aktif. Harap berikan tanggal berakhirnya jabatan saat ini sebelum menambahkan jabatan baru.']);
        }

        $riwayatJabatan->update($validatedData);

        return redirect()->route('user.edit', $user_id)->with('status', 'Riwayat Jabatan updated successfully!');
    }

    public function destroy($user_id, $id)
    {
        $riwayatJabatan = RiwayatJabatan::findOrFail($id);
        $riwayatJabatan->delete();

        return redirect()->route('user.edit', $user_id)->with('status', 'Riwayat Jabatan deleted successfully!');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'id_jabatan' => 'required|exists:jabatan,id',
            'tanggal_mulai' => 'required|date|before_or_equal:today',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai|before_or_equal:today',
        ]);
    }
}
