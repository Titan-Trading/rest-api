<?php

namespace App\Console;

use App\Console\Commands\GenerateMetricsCommand;
use App\Console\Commands\MessageBusConsumerCommand;
use App\Console\Commands\RegisterServiceCommand;
use App\Console\Commands\UnregisterServiceCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RegisterServiceCommand::class,
        UnregisterServiceCommand::class,
        GenerateMetricsCommand::class,
        // MessageBusConsumerCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
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
