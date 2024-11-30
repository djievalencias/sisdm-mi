<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('pages.jabatan.index', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        Jabatan::create($validated);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('pages.jabatan.show', compact('jabatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('pages.jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $validated = $this->validateRequest($request, $jabatan->id_jabatan);

        $jabatan->update($validated);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    /**
     * Validate incoming request data.
     */
    protected function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'id_grup' => 'nullable|integer|exists:grups,id',
            'nama' => 'required|string|max:64',
        ];

        if ($id) {
            // Validation for update
            $rules['id_jabatan'] = 'required|integer|unique:jabatans,id_jabatan,' . $id . ',id_jabatan';
        } else {
            // Validation for create
            $rules['id_jabatan'] = 'required|integer|unique:jabatans,id_jabatan';
        }

        return $request->validate($rules);
    }
}
