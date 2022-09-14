<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('recycle:check')->dailyAt('0:15');


        $schedule->command('activity:sync_data yesterday')
            ->runInBackground()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/log_job_sync_data_report_detail_yesterday.txt'))
            ->dailyAt('1:00');

        $schedule->command('activity:sync_data today')
            ->runInBackground()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/log_job_sync_data_report_detail_today.txt'))
            ->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
