<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Departemen;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('pages.shift.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Departemen::all();
        return view('pages.shift.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|unique:shift|max:255',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'senin' => 'boolean',
            'selasa' => 'boolean',
            'rabu' => 'boolean',
            'kamis' => 'boolean',
            'jumat' => 'boolean',
            'sabtu' => 'boolean',
            'minggu' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        Shift::create($validated);

        return redirect()->route('shift.index')->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        return view('pages.shift.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        return view('pages.shift.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255|unique:shift,nama,' . $shift->id,
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'senin' => 'boolean',
            'selasa' => 'boolean',
            'rabu' => 'boolean',
            'kamis' => 'boolean',
            'jumat' => 'boolean',
            'sabtu' => 'boolean',
            'minggu' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $shift->update($validated);

        return redirect()->route('shift.index')->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('shift.index')->with('success', 'Shift deleted successfully.');
    }
}
