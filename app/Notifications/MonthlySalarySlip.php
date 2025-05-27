<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MonthlySalarySlip extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // instance MonthlySalary
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $monthlySalary = $this->data;

        // Buat PDF dari blade
        $pdf = Pdf::loadView('pages.user.Monthly_Salary_slip', [
            'employee' => $notifiable,
            'data' => $this->data, // menggunakan $this->data (MonthlySalary) yang telah dikirim ke konstruktor
        ]);

        // Simpan PDF dengan nama unik
        $timestamp = now()->format('Ymd_His');
        $pdfPath = "slips/monthly/slip_{$notifiable->nip}_{$timestamp}.pdf";

        // Simpan PDF ke storage
        Storage::put($pdfPath, $pdf->output());

        // Ambil keterangan
        $keterangan = $this->data->keterangan ?? 'slip gaji bulanan Anda';

        return (new MailMessage)
            ->subject('Slip Gaji Bulanan')
            ->greeting('Halo ' . $notifiable->nama . '!')
            ->line('Berikut terlampir '. $keterangan)
            ->attachData($pdf->output(), 'slip-bulanan.pdf', [
                'mime' => 'application/pdf',
            ])
            ->line('Terima kasih telah bekerja dengan baik.')
            ->line('Hormat kami, ')
            ->salutation('HRD PT. Pusat Grosir Sidoarjo');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
