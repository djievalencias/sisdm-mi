<?php

namespace App\Http\Controllers;

use App\Models\Tunjangan;
use Illuminate\Http\Request;

class TunjanganController extends Controller
{
    public function store(Request $request, $id_payroll)
    {
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama tunjangan harus diisi.',
            'nominal.required' => 'Nominal tunjangan harus diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
        ]);

        // Check if the tunjangan with the same name already exists
        if (Tunjangan::where('id_payroll', $id_payroll)->where('nama', $request->nama)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Tunjangan ini sudah ada untuk payroll ini.']);
        }

        // Create the tunjangan
        Tunjangan::create([
            'id_payroll' => $id_payroll,
            'nama' => $request->nama,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('payroll.edit', $id_payroll)->with('success', 'Tunjangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tunjangan = Tunjangan::findOrFail($id);
        return view('tunjangan.edit', compact('tunjangan'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Find and update the tunjangan
        $tunjangan = Tunjangan::findOrFail($id);
        $tunjangan->update([
            'nama' => $request->nama,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('payroll.edit', $tunjangan->id_payroll)->with('success', 'Tunjangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Find and delete the tunjangan
        $tunjangan = Tunjangan::findOrFail($id);
        $tunjangan->delete();

        return redirect()->back()->with('success', 'Tunjangan berhasil dihapus.');
    }
}
