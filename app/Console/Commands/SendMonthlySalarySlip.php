<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\MonthlySalary;
use App\Notifications\MonthlySalarySlip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendMonthlySalarySlip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:monthly-slip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim slip gaji bulanan ke pegawai yang punya email';

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
    public function handle()
    {
        try {
            $employees = Employee::whereNotNull('email')->get();

            foreach ($employees as $employee) {
                $slip = MonthlySalary::where('nip', $employee->nip)->latest()->first();
                if ($slip) {
                    $employee->notify(new MonthlySalarySlip($slip));
                }
            }
            $this->info('Pengiriman selesai.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim slip gaji lembur: ' . $e->getMessage());
        }
    }

}
