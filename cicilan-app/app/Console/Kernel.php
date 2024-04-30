<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected $commands = [
        \App\Console\Commands\sendLunasEmail::class,
    ];

    protected function schedule(Schedule $schedule)
    {
    // 
        // Menjadwalkan perintah untuk berjalan setiap hari pada jam 3
        $schedule->command('app:send-lunas-email')->dailyAt('03:00');
            // $schedule->command('app:send-lunas-email')->hourly('03:00');
        // $schedule->command('app:send-lunas-email')
        //  ->cron('03.00.00')
        //  ->when(function () {
        //      $currentMinute = now()->minute;
        //      return $currentMinute = 59 || $currentMinute >= 0 && $currentMinute <= 3;
        //  });

         
        
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
