<?php

namespace App\Notifications;

use App\Models\CutiPerizinan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(public CutiPerizinan $cuti) {}

    /** Seconds to wait between retries before the job hits the DLQ. */
    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New leave request awaiting your review'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->nama]))
            ->line(__(':name submitted a leave request from :start to :end.', [
                'name' => $this->cuti->user->nama,
                'start' => $this->cuti->tanggal_mulai,
                'end' => $this->cuti->tanggal_selesai,
            ]))
            ->line(__('Notes: :notes', ['notes' => $this->cuti->keterangan]));
    }
}
