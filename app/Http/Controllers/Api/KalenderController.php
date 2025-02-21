<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kalender;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    /**
     * Get all events with pagination.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10); // Default 10 per page

        // Select only necessary fields
        $events = Kalender::select('id', 'judul', 'tanggal_mulai', 'tanggal_selesai', 'tipe', 'created_by', 'updated_by')
            ->orderBy('tanggal_mulai', 'asc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $events
        ], 200);
    }
}
