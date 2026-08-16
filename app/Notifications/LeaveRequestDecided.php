<?php

namespace App\Notifications;

use App\Models\CutiPerizinan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestDecided extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(
        public CutiPerizinan $cuti,
        public string $decision, // 'disetujui' | 'ditolak'
    ) {}

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
        $approved = $this->decision === 'disetujui';

        return (new MailMessage)
            ->subject($approved ? __('Your leave request has been approved') : __('Your leave request has been rejected'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->nama]))
            ->line(__('Your leave request from :start to :end has been :decision.', [
                'start' => $this->cuti->tanggal_mulai,
                'end' => $this->cuti->tanggal_selesai,
                'decision' => $approved ? __('approved') : __('rejected'),
            ]))
            ->line(__('Notes: :notes', ['notes' => $this->cuti->keterangan]));
    }
}
