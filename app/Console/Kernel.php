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
        Commands\ClientMigrateDataBase::class,
        Commands\ClientRollbcakMigrate::class,
        Commands\UpdateRedis::class,
        Commands\NewChanges::class,
        Commands\SendPushNotification::class,
        Commands\RunSingleSeederCommand::class,
        Commands\BulkUploadAllocationCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
           $schedule->command('create:batch')->everyFiveMinutes();
           $schedule->command('BulkUploadAllocation:cron')->everyMinute();
        // $schedule->command('push:send')->everyMinute();
       //  $schedule->command('queue:restart')->everyMinute()
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
