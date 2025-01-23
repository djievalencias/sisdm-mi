<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use Illuminate\Http\Request;
use App\Models\User;


class KantorController extends Controller
{
    public function index()
    {
        $kantor = Kantor::all();
        return view('pages.kantor.index', compact('kantor'));
    }

    public function create()
    {
        // Fetch only users with a jabatan containing "Manager"
    $managers = User::whereHas('jabatan', function ($query) {
        $query->where('nama', 'like', '%Manager%'); // Adjust this condition as needed
    })->get();
        return view('pages.kantor.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'koordinat_x' => 'required|numeric',
            'koordinat_y' => 'required|numeric',
            'radius' => 'required|numeric',
            'id_manager' => 'nullable|exists:users,id',
        ]);

        Kantor::create($request->all());
        return redirect()->route('kantor.index')->with('status', 'Kantor created successfully!');
    }

    public function edit(Kantor $kantor)
    {
        $kantor = Kantor::findOrFail($id);
        // Fetch only users with a jabatan containing "Manager"
    $managers = User::whereHas('jabatan', function ($query) {
        $query->where('nama', 'like', '%Manager%'); // Adjust this condition as needed
    })->get();
        return view('pages.kantor.edit', compact('kantor', 'managers'));
    }

    public function update(Request $request, Kantor $kantor)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'koordinat_x' => 'required|numeric',
            'koordinat_y' => 'required|numeric',
            'radius' => 'required|numeric',
            'id_manager' => 'nullable|exists:users,id',
        ]);

        $kantor->update($request->all());
        return redirect()->route('kantor.index')->with('status', 'Kantor updated successfully!');
    }

    public function destroy(Kantor $kantor)
    {
        $kantor->delete();
        return redirect()->route('kantor.index')->with('status', 'Kantor deleted successfully!');
    }
}
