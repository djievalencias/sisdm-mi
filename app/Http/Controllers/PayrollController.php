<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Daftar payroll
     */
    public function index()
    {
        $payrolls = Payroll::with('user')->orderBy('tanggal_payroll', 'desc')->paginate(10);
        return view('pages.payroll.index', compact('payrolls'));
    }

    /**
     * Form buat payroll
     */
    public function create()
    {
        $users = User::all();
        return view('pages.payroll.create', compact('users'));
    }

    /**
     * Simpan payroll
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_payroll' => 'required|date',
            'gaji_pokok' => 'nullable|numeric',
            'upah_lembur' => 'nullable|numeric',
            'gaji_tgl_merah' => 'nullable|numeric',
            'upah_lembur_tgl_merah' => 'nullable|numeric',
            'iuran_bpjs_kantor' => 'nullable|numeric',
            'iuran_bpjs_karyawan' => 'nullable|numeric',
        ]);

        // Pastikan tidak double
        $exists = Payroll::where('id_user', $request->id_user)
            ->whereDate('tanggal_payroll', $request->tanggal_payroll)
            ->exists();
        if ($exists) {
            return back()->withErrors('Payroll sudah ada untuk user & tanggal tersebut.')->withInput();
        }

        // Simpan payroll
        Payroll::create([
            'id_user' => $request->id_user,
            'tanggal_payroll' => $request->tanggal_payroll,
            'gaji_pokok' => $request->gaji_pokok ?? 0,
            'upah_lembur' => $request->upah_lembur ?? 0,
            'gaji_tgl_merah' => $request->gaji_tgl_merah ?? 0,
            'upah_lembur_tgl_merah' => $request->upah_lembur_tgl_merah ?? 0,
            'iuran_bpjs_kantor' => $request->iuran_bpjs_kantor ?? 0,
            'iuran_bpjs_karyawan' => $request->iuran_bpjs_karyawan ?? 0,
            'is_reviewed' => false,
            'status' => false,
        ]);

        return redirect()->route('payroll.index')->with('success','Payroll berhasil dibuat.');
    }

    /**
     * Tampilkan satu data payroll
     */
    public function show($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        return view('pages.payroll.show', compact('payroll'));
    }

    /**
     * Form edit payroll
     */
    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        $users = User::all();
        return view('pages.payroll.edit', compact('payroll','users'));
    }

    /**
     * Update payroll
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_payroll' => 'required|date',
            'gaji_pokok' => 'nullable|numeric',
            'upah_lembur' => 'nullable|numeric',
            'gaji_tgl_merah' => 'nullable|numeric',
            'upah_lembur_tgl_merah' => 'nullable|numeric',
            'iuran_bpjs_kantor' => 'nullable|numeric',
            'iuran_bpjs_karyawan' => 'nullable|numeric',
            'is_reviewed' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $payroll = Payroll::findOrFail($id);
        $payroll->update($request->all());

        return redirect()->route('payroll.index')->with('success','Payroll berhasil diperbarui.');
    }

    /**
     * Hapus payroll
     */
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('payroll.index')->with('success','Payroll berhasil dihapus.');
    }
}
