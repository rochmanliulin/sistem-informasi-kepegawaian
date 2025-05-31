<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OvertimeSalary;
use App\Models\Employee;
use App\Notifications\OvertimeSalaryNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendOvertimeSalarySlip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:overtime-sLip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim slip gaji lembur ke pegawai yang punya email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */


    // Melakukan pengecekan tanggal terbit pada slip lembur
    public function handle()
    {
        // Ambil tanggal hari ini dalam timezone Asia/Jakarta
        $today = Carbon::now('Asia/Jakarta')->toDateString(); // Contoh: '2025-05-30'

        // Ambil semua data slip lembur yang memiliki tgl_terbit yang sama dengan hari ini
        $slips = OvertimeSalary::whereDate('tgl_terbit', $today)->get();

        // Jika tidak ada slip lembur yang diterbitkan hari ini, hentikan proses
        if ($slips->isEmpty()) {
            \Log::info("Tidak ada slip lembur yang dikirim karena tidak ada yang terbit hari ini: $today");
            return;
        }

        // Loop kirim slip lembur setelah di filter tanggal terbit
        foreach ($slips as $slip) {
            $employee = $slip->employee;

            if ($employee && $employee->email) {
                try {
                    $employee->notify(new OvertimeSalaryNotification($slip));
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim slip ke {$employee->email}: " . $e->getMessage());
                }
            }
        }
    }
}
