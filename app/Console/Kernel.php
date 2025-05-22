<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Employee;
use App\Models\OvertimeSalary;
use App\Notifications\OvertimeSalarySlip;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SendPasswordResetEmails::class,
        \App\Console\Commands\CalculateSalary::class,
        \App\Console\Commands\SendOvertimeSalarySlips::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     protected function schedule(Schedule $schedule)
     {
         // Menjadwalkan kirim slip lembur
         $schedule->command('send:overtime-slip')
         ->weeklyOn(3,'15:23') // Setiap hari sabtu jam 06:00
         ->timezone('Asia/Jakarta');


        // Menjadwalkan kirim slip gaji bulanan
         $schedule->command('salary:send-slips')
         ->monthlyOn(1, '06:00') // Setiap tanggal 1 jam 06:00
         ->timezone('Asia/Jakarta');
     }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
