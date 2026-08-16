<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Machine keys stored as activity descriptions, translated for display.
     * Single source for both the filter dropdown and the table labels.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            'leave.approved' => __('Leave request approved'),
            'leave.rejected' => __('Leave request rejected'),
            'leave.undone' => __('Leave request decision undone'),
            'payroll.created' => __('Payroll created'),
            'payroll.updated' => __('Payroll updated'),
            'payroll.deleted' => __('Payroll deleted'),
            'payroll.reviewed' => __('Payroll reviewed'),
            'payroll.paid' => __('Payroll marked as paid'),
            'user.created' => __('Employee created'),
            'user.updated' => __('Employee updated'),
            'user.archived' => __('Employee archived'),
            'user.restored' => __('Employee restored'),
            'attendance.deleted' => __('Attendance deleted'),
            'pengumuman.created' => __('Announcement created'),
            'pengumuman.deleted' => __('Announcement deleted'),
            'queue.retried' => __('Failed job retried'),
            'queue.forgotten' => __('Failed job deleted'),
            'queue.flushed' => __('All failed jobs deleted'),
        ];
    }

    public function index(Request $request)
    {
        // Filters run before the cap so it applies to the filtered set.
        // Client-side DataTable (project convention), hence the row cap.
        $activities = Activity::with('causer')
            ->when($request->filled('description'), fn ($q) => $q->where('description', $request->input('description')))
            ->when($request->filled('causer_id'), fn ($q) => $q->where('causer_id', $request->input('causer_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to')))
            ->latest()
            ->limit(500)
            ->get();

        $labels = self::labels();
        $users = User::orderBy('nama')->get(['id', 'nama']);

        return view('pages.activity_log.index', compact('activities', 'labels', 'users'));
    }
}
