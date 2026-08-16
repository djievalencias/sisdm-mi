<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FailedJobController extends Controller
{
    public function index()
    {
        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true) ?: [];

                // For queued notifications the display name is the generic
                // SendQueuedNotifications job; the notification class inside
                // the serialized command is what admins actually care about.
                $name = $payload['displayName'] ?? __('Unknown');
                if (preg_match('/App\\\\Notifications\\\\(\w+)/', $payload['data']['command'] ?? '', $m)) {
                    $name = $m[1];
                }

                $job->job_name = class_basename($name);
                $job->error_summary = strtok((string) $job->exception, "\n");

                return $job;
            });

        return view('pages.failed_jobs.index', compact('failedJobs'));
    }

    public function retry(string $uuid)
    {
        Artisan::call('queue:retry', ['id' => [$uuid]]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['uuid' => $uuid])
            ->log('queue.retried');

        return redirect()->route('failed-jobs.index')->with('success', __('Job queued for retry.'));
    }

    public function retryAll()
    {
        $count = DB::table('failed_jobs')->count();
        Artisan::call('queue:retry', ['id' => ['all']]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['count' => $count])
            ->log('queue.retried');

        return redirect()->route('failed-jobs.index')->with('success', __('All failed jobs queued for retry.'));
    }

    public function destroy(string $uuid)
    {
        Artisan::call('queue:forget', ['id' => $uuid]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['uuid' => $uuid])
            ->log('queue.forgotten');

        return redirect()->route('failed-jobs.index')->with('success', __('Failed job deleted.'));
    }

    public function flush()
    {
        $count = DB::table('failed_jobs')->count();
        Artisan::call('queue:flush');

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['count' => $count])
            ->log('queue.flushed');

        return redirect()->route('failed-jobs.index')->with('success', __('All failed jobs deleted.'));
    }
}
