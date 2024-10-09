<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Ratchet\App;

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
        //luu uptime records
        $schedule->call(function () {
            $uptimeRecord = new \App\Models\UptimeRecord();
            $uptimeRecord->saveLogs();
        })->everyMinute();

        //check IP dich, ngay het han domain
        $schedule->call(function () {
            $uptimeRecord = new \App\Models\UptimeRecord();
            $uptimeRecord->whoisAPI();
        })->everyFifteenMinutes();

        //gui mail, tele bot neu phat hien loi
        $schedule->command('process:incidents')->withoutOverlapping()->everyTenMinutes();

        //luu logs va xoa uptime records trong 1 ngay => toi uu du lieu
        $schedule->command('process:save_logs')->dailyAt(1);
        $schedule->command('process:delete_logs')->dailyAt(2);
        $schedule->command('process:reset_flags')->dailyAt(2);

//        $schedule->call(function () {
//            $uptimeRecord = new \App\Models\UptimeRecord();
//            $uptimeRecord->saveMonitors();
//        })->everyMinute();
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

    protected $commands = [
        // ...
        \App\Console\Commands\ProcessIncidentsCommand::class,
        \App\Console\Commands\SaveLogsCommand::class,
        \App\Console\Commands\DeleteLogsCommand::class,
    ];
}
