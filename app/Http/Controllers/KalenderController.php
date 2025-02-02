<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kalender;
use Illuminate\Support\Facades\Auth;

class KalenderController extends Controller
{
    // Display all events
    public function index()
    {
        $events = Kalender::orderBy('tanggal_mulai', 'asc')->get();
        return view('pages.kalender.index', compact('events'));
    }

    // Show form to create a new event
    public function create()
    {
        return view('pages.kalender.create');
    }

    // Store new event
    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        $event = Kalender::create(array_merge($validatedData, [
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]));

        $this->generateRecurringEvents($event);

        return redirect()->route('kalender.index')->with('success', 'Event berhasil dibuat!');
    }

    // Show details of a single event
    public function show(Kalender $kalender)
    {
        return view('pages.kalender.show', compact('kalender'));
    }

    // Show form to edit an event
    public function edit(Kalender $kalender)
    {
        return view('pages.kalender.edit', compact('kalender'));
    }

    // Update event
    public function update(Request $request, Kalender $kalender)
    {
        $validatedData = $this->validateRequest($request);

        $kalender->update(array_merge($validatedData, [
            'updated_by' => Auth::id(),
        ]));

        // Delete old repeating events if recurrence is turned off
        if ($validatedData['repeat_type'] === 'never') {
            Kalender::where('judul', $kalender->judul)
                ->where('id', '!=', $kalender->id)
                ->delete();
        } else {
            $this->generateRecurringEvents($kalender, true);
        }

        return redirect()->route('kalender.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Delete event
    public function destroy(Kalender $kalender)
    {
        $kalender->delete();
        return redirect()->route('kalender.index')->with('success', 'Event berhasil dihapus!');
    }

    /**
     * Validate the request for creating or updating an event.
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:hari_libur,meeting,acara,lainnya',
            'repeat_type' => 'required|in:never,weekly,monthly,yearly',
            'repeat_until' => 'nullable|date|after:tanggal_mulai|required_if:repeat_type,weekly,monthly,yearly'
        ]);
    }

    private function generateRecurringEvents(Kalender $event, $update = false)
    {
        if ($event->repeat_type === 'never' || !$event->repeat_until) {
            return;
        }

        $startDate = $event->tanggal_mulai;
        $endDate = $event->repeat_until;

        $interval = match ($event->repeat_type) {
            'weekly' => '+1 week',
            'monthly' => '+1 month',
            'yearly' => '+1 year',
            default => null,
        };

        if (!$interval) return;

        // Remove old repeating events if updating
        if ($update) {
            Kalender::where('judul', $event->judul)
                ->where('id', '!=', $event->id)
                ->delete();
        }

        $nextDate = strtotime($interval, strtotime($startDate));

        while ($nextDate <= strtotime($endDate)) {
            Kalender::create([
                'tanggal_mulai' => date('Y-m-d', $nextDate),
                'tanggal_selesai' => null,
                'judul' => $event->judul,
                'tipe' => $event->tipe,
                'repeat_type' => 'never',
                'created_by' => $event->created_by,
                'updated_by' => $event->updated_by,
            ]);

            $nextDate = strtotime($interval, $nextDate);
        }
    }
}
