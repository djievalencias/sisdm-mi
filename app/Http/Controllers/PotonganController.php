<?php

namespace App\Http\Controllers;

use App\Models\Potongan;
use Illuminate\Http\Request;

class PotonganController extends Controller
{
    public function store(Request $request, $id_payroll)
    {
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama potongan harus diisi.',
            'nominal.required' => 'Nominal potongan harus diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
        ]);

        // Check if a potongan with the same name already exists for this payroll
        if (Potongan::where('id_payroll', $id_payroll)->where('nama', $request->nama)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Potongan ini sudah ada untuk payroll ini.']);
        }

        // Create the new potongan
        Potongan::create([
            'id_payroll' => $id_payroll,
            'nama' => $request->nama,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('payroll.edit', $id_payroll)->with('success', 'Potongan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $potongan = Potongan::findOrFail($id);
        return view('potongan.edit', compact('potongan'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Find and update the existing potongan
        $potongan = Potongan::findOrFail($id);
        $potongan->update([
            'nama' => $request->nama,
            'nominal' => $request->nominal,
        ]);

        return redirect()->route('payroll.edit', $potongan->id_payroll)->with('success', 'Potongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Find and delete the potongan
        $potongan = Potongan::findOrFail($id);
        $potongan->delete();

        return redirect()->back()->with('success', 'Potongan berhasil dihapus.');
    }
}
