<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('schedules:verify')
        ->everyFiveMinutes()// ou ->everyMinute(), ->hourly(), etc.
        ->timezone('Africa/Kinshasa')
        ->withoutOverlapping(); 

        $schedule->command('presence:send-daily-report')->dailyAt('10:00')->timezone("Africa/Kinshasa");
        $schedule->command('presence:send-daily-report')->dailyAt('20:00')->timezone("Africa/Kinshasa");

        $schedule->command('plannings:create')->dailyAt('20:30')->timezone("Africa/Kinshasa");

        $schedule->command('backup:send')->dailyAt('00:00')
        ->timezone("Africa/Kinshasa")
        ->withoutOverlapping();
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
