<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\OvertimeSalary;
use App\Notifications\OvertimeSalarySlip;
use Illuminate\Support\Facades\Log;


class SendOvertimeSalarySlips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:overtime-slips';

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
    public function handle()
    {
        try {
            $this->info('Mengirim slip gaji lembur...');
            $employees = Employee::whereNotNull('email')->get();

            foreach ($employees as $employee) {
                $slip = OvertimeSalary::where('nip', $employee->nip)->latest()->first();
                if ($slip) {
                    $employee->notify(new OvertimeSalarySlip($slip));
                    $this->info("Slip terkirim ke: {$employee->email}");
                }
            }
            $this->info('Pengiriman selesai.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim slip gaji lembur: ' . $e->getMessage());
        }
    }
}
