<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use App\Models\Departemen;
use App\Models\PenjadwalanShift;

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

    /**
     * Show the form for assigning users to a shift.
     */
    public function assignForm($shiftId)
    {
        $shift = Shift::findOrFail($shiftId);
        $users = User::all(); // Fetch all users to select from
        $assignedUsers = PenjadwalanShift::where('id_shift', $shiftId)->pluck('id_user')->toArray();

        return view('pages.shift.assign', compact('shift', 'users', 'assignedUsers'));
    }

    /**
     * Assign users to a shift.
     */
    public function assign(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        // Get user IDs from the request (default to an empty array if not provided)
        $userIds = $request->input('users', []);

        // Remove all current assignments for this shift
        PenjadwalanShift::where('id_shift', $shift->id)->delete();

        // Assign users to the shift if any are provided
        if (!empty($userIds)) {
            foreach ($userIds as $userId) {
                PenjadwalanShift::create([
                    'id_user' => $userId,
                    'id_shift' => $shift->id,
                    'is_ditampilkan' => true,
                ]);
            }
        }

        return redirect()->route('shift.index')->with(
            'success',
            !empty($userIds) ? 'Users assigned to the shift successfully.' : 'All users unassigned from the shift.'
        );
    }


    /**
     * Unassign a user from a shift.
     */
    public function unassign($shiftId, $userId)
    {
        $assignment = PenjadwalanShift::where('id_shift', $shiftId)
            ->where('id_user', $userId)
            ->first();

        if ($assignment) {
            $assignment->delete();
            return redirect()->back()->with('success', 'User unassigned from shift successfully.');
        }

        return redirect()->back()->with('error', 'User is not assigned to this shift.');
    }
}
