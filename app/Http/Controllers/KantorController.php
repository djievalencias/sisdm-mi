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
        $managers = User::whereHas('riwayatJabatan', function ($query) {
            $query->whereNull('tanggal_selesai') // Only current positions
                  ->whereHas('jabatan', function ($subQuery) {
                      $subQuery->where('nama', 'like', '%Manager%'); // Jabatan contains "Manager"
                  });
        })->get();
        
        return view('pages.kantor.create', compact('managers'));     
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateKantor($request);

        Kantor::create($validatedData);
        return redirect()->route('kantor.index')->with('status', 'Kantor created successfully!');
    }

    public function edit(Kantor $kantor)
    {
        $managers = User::whereHas('riwayatJabatan', function ($query) {
            $query->whereNull('tanggal_selesai') // Only current positions
                  ->whereHas('jabatan', function ($subQuery) {
                      $subQuery->where('nama', 'like', '%Manager%'); // Jabatan contains "Manager"
                  });
        })->get();

        return view('pages.kantor.edit', compact('kantor', 'managers'));
    }

    public function update(Request $request, Kantor $kantor)
    {
        $validatedData = $this->validateKantor($request, $kantor->id);

        $kantor->update($validatedData);
        return redirect()->route('kantor.index')->with('status', 'Kantor updated successfully!');
    }

    public function destroy(Kantor $kantor)
    {
        $kantor->delete();
        return redirect()->route('kantor.index')->with('status', 'Kantor deleted successfully!');
    }

    private function validateKantor(Request $request, $id = null)
    {
        return $request->validate([
            'nama' => 'required|string|max:255|unique:kantor,nama' . ($id ? ",$id" : ''),
            'alamat' => 'required|string|unique:kantor,alamat' . ($id ? ",$id" : ''),
            'koordinat_x' => 'required|numeric|between:-180,180',
            'koordinat_y' => 'required|numeric|between:-90,90',
            'radius' => 'required|numeric|min:0',
            'id_manager' => 'nullable|exists:users,id',
        ]);
    }
}
