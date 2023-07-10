<?php

namespace App\Console;

use Spatie\ShortSchedule\ShortSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'App\Console\Commands\SendResponsesCommand'
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
    }

    protected function shortSchedule(ShortSchedule $shortSchedule): void
    {
        $shortSchedule->command('app:send-responses')->everySecond(3)->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
