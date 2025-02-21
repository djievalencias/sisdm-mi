<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use App\Models\Departemen;
use Illuminate\Http\Request;

class GrupController extends Controller
{
    public function index()
    {
        $grup = Grup::with('departemen')->get();
        return view('pages.grup.index', compact('grup'));
    }

    public function create()
    {
        $departemen = Departemen::all();
        return view('pages.grup.create', compact('departemen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_departemen' => 'nullable|exists:departemen,id',
            'nama' => 'required|string|max:255',
        ]);

        Grup::create($request->all());
        return redirect()->route('grup.index')->with('status', 'Grup created successfully!');
    }

    public function edit(Grup $grup)
    {
        $departemen = Departemen::all();
        return view('pages.grup.edit', compact('grup', 'departemen'));
    }

    public function update(Request $request, Grup $grup)
    {
        $request->validate([
            'id_departemen' => 'nullable|exists:departemen,id',
            'nama' => 'required|string|max:255',
        ]);

        $grup->update($request->all());
        return redirect()->route('grup.index')->with('status', 'Grup updated successfully!');
    }

    public function destroy(Grup $grup)
    {
        $grup->delete();
        return redirect()->route('grup.index')->with('status', 'Grup deleted successfully!');
    }

    public function getByDepartemen($id)
    {
        $grup = Grup::where('departemen_id', $id)->get();
        return response()->json($grup);
    }
}
