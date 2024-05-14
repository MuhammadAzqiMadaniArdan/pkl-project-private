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
        // Perintah schedue untuk menjaankan cmmand pada waktu perhari hjanya di jam 3
        info("test");
        $schedule->command('app:send-lunas-email')->dailyAt('00:00');
         
        $schedule->call(function () {
            file_put_contents(storage_path('logs/cronjob.log'), 'Cronjob is running at ' . now() . PHP_EOL, FILE_APPEND);
        })->everyMinute();
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
