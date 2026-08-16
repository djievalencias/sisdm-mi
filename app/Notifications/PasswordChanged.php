<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

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
            ->subject(__('Your password was changed'))
            ->greeting(__('Hello :name,', ['name' => $notifiable->nama]))
            ->line(__('Your account password was changed on :time.', ['time' => now()->format('d M Y, H:i')]))
            ->line(__('If you did not make this change, contact HR immediately.'));
    }
}
