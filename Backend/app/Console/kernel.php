<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\UpdateExpiredTransactions;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Menjalankan setiap hari pukul 00:00
        $schedule->command('transaksi:update-expired')->daily();

        // Atau untuk testing cepat:
        // $schedule->command('transaksi:update-expired')->everyMinute();
    }
}
