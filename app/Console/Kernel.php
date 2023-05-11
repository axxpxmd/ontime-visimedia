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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('Check:out')->dailyAt('22:00');
        $m = date('m');
        $schedule->command('Hitung:denda --month='.$m)->dailyAt('00:00');
        $schedule->command('Check:in')->dailyAt('01:00');
        $schedule->command('send:notif checkin')->dailyAt('07:20');
        $schedule->command('send:notif checkout')->dailyAt('16:30');
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
