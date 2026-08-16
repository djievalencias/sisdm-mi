<?php

namespace App\Notifications;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayslipPaid extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function __construct(public Payroll $payroll) {}

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
        // The worker re-fetches a bare Payroll (SerializesModels); load what
        // the slip view needs up front instead of lazy-loading row by row.
        $this->payroll->loadMissing(['user', 'reviewer', 'tunjangan', 'potongan']);

        $period = $this->payroll->tanggal_payroll->translatedFormat('F Y');
        $pdf = Pdf::loadView('pages.payroll.slip', ['payroll' => $this->payroll])->output();

        return (new MailMessage)
            ->subject(__('Your payslip for :period', ['period' => $period]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->nama]))
            ->line(__('Your salary has been paid. Your payslip is attached.'))
            ->attachData($pdf, 'payslip-'.$this->payroll->tanggal_payroll->format('Y-m').'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
