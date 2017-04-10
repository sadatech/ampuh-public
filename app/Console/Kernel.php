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
        'App\Console\Commands\BaConfiguration',
        'App\Console\Commands\StoreConfiguration',
        'App\Console\Commands\MigrateForConfigCommand',
        'App\Console\Commands\ClearFulfilledWipCommand',
        'App\Console\Commands\SpCheckingCommand',
        'App\Console\Commands\ClassUpgradeCommand',
        'App\Console\Commands\NotifyAro',
        'App\Console\Commands\NotifyAroDaily',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('configuration:ba')->monthlyOn(1);
        $schedule->command('configuration:store')->monthlyOn(1);
        $schedule->command('clear:wip')->monthlyOn(1);
        $schedule->command('class:upgrade')->monthlyOn(1);
        $schedule->command('sp:checking')->daily();
        $schedule->command('notify:weekly')->weekly();
        $schedule->command('notify:daily')->dailyAt('9');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
