<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\OrderStatus;
use App\Console\Commands\UpdatePrice;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        OrderStatus::class,
        UpdatePrice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Core Automation: Update SMM order statuses every 5 minutes
        // withoutOverlapping prevents the command from starting if the previous one is still running
        $schedule->command('update:status')
                 ->everyFiveMinutes()
                 ->withoutOverlapping();
                 
        $schedule->command('update:price')
                 ->dailyAt('03:00')
                 ->withoutOverlapping();         

        // 2. Queue Maintenance: Restart workers daily to refresh memory
        $schedule->command('queue:restart')->daily();

        // Optional: If you have a lot of broadcast jobs
        // $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
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