<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Kantor;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index()
    {
        $departemen = Departemen::with('kantor')->get();
        return view('pages.departemen.index', compact('departemen'));
    }

    public function create()
    {
        $kantor = Kantor::all();
        return view('pages.departemen.create', compact('kantor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kantor' => 'nullable|exists:kantor,id',
            'nama' => 'required|string|max:255',
        ]);

        Departemen::create($request->all());
        return redirect()->route('departemen.index')->with('status', 'Departemen created successfully!');
    }

    public function edit(Departemen $departemen)
    {
        $kantor = Kantor::all();
        return view('pages.departemen.edit', compact('departemen', 'kantor'));
    }

    public function update(Request $request, Departemen $departemen)
    {
        $request->validate([
            'id_kantor' => 'nullable|exists:kantor,id',
            'nama' => 'required|string|max:255',
        ]);

        $departemen->update($request->all());
        return redirect()->route('departemen.index')->with('status', 'Departemen updated successfully!');
    }

    public function destroy(Departemen $departemen)
    {
        $departemen->delete();
        return redirect()->route('departemen.index')->with('status', 'Departemen deleted successfully!');
    }

    public function getByKantor($id)
    {
        $departemen = Departemen::where('kantor_id', $id)->get();
        return response()->json($departemen);
    }
}
