<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Kalender;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    /**
     * Get all events.
     */
    public function index()
    {
        $events = Kalender::orderBy('tanggal_mulai', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List of events',
            'data' => $events
        ], 200);
    }
}
