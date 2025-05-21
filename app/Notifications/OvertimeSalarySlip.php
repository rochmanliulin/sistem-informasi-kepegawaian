<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OvertimeSalarySlip extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // kirim instance OvertimeSalary
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $overtimeSalary = $this->data;

        $total = $overtimeSalary->total_uang_lembur
        + $overtimeSalary->doa
        + $overtimeSalary->premi_hadir
        + $overtimeSalary->premi_lembur
        + $overtimeSalary->total_uang_kopi
        + $overtimeSalary->total_uang_lembur_minggu
        + $overtimeSalary->total_uang_makan;

        if (strtolower($overtimeSalary->employee->status) == 'Pegawai Harian') {
            $total += $overtimeSalary->gaji;
        }

        $overtimeSalary->total = $total;

        // Menggunakan data yang diteruskan untuk memuat tampilan PDF
        $pdf = Pdf::loadView('pages.user.Overtime_Salary_Slip', [
            'employee' => $notifiable,
            'data' => $this->data, // menggunakan $this->data (OvertimeSalary) yang telah dikirim ke konstruktor
        ]);

        // Membuat nama file PDF dengan timestamp
        $timestamp = now()->format('Ymd_His');
        $pdfPath = "slips/lembur_{$notifiable->nip}_{$timestamp}.pdf";

        // Menyimpan PDF ke storage
        Storage::put($pdfPath, $pdf->output());

        // Ambil keterangan
        $keterangan = $this->data->keterangan ?? 'slip gaji lembur Anda';

        // Mengirimkan email dengan lampiran PDF
        return (new MailMessage)
            ->subject('Slip Gaji Lembur')
            ->greeting('Halo, ' . $notifiable->nama . '!')
            ->line('Berikut terlampir ' . $keterangan)
            ->attach(storage_path('app/' . $pdfPath)) // Melampirkan file PDF yang telah disimpan
            ->line('Terima kasih telah bekerja dengan baik.')
            ->line('Hormat kami, ')
            ->salutation('HRD PT. Pusat Grosir Sidoarjo');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
