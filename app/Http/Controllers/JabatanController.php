<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Grup;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::with('grup')->get();
        return view('pages.jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        $grup = Grup::all();
        return view('pages.jabatan.create', compact('grup'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_grup' => 'nullable|exists:grup,id',
            'nama' => 'required|string|max:255|unique:jabatan,nama',
            'description' => 'nullable|string',
        ]);

        Jabatan::create($request->all());
        return redirect()->route('jabatan.index')->with('status', 'Jabatan created successfully.');
    }

    public function edit(Jabatan $jabatan)
    {
        $grup = Grup::all();
        return view('pages.jabatan.edit', compact('jabatan', 'grup'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'id_grup' => 'nullable|exists:grup,id',
            'nama' => 'required|string|max:255|unique:jabatan,nama,' . $jabatan->id,
            'description' => 'nullable|string',
        ]);

        $jabatan->update($request->all());
        return redirect()->route('jabatan.index')->with('status', 'Jabatan updated successfully.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('jabatan.index')->with('status', 'Jabatan deleted successfully.');
    }

    public function getByDepartemen($id)
    {
        $jabatan = Jabatan::where('departemen_id', $id)->get();
        return response()->json($jabatan);
    }
}
