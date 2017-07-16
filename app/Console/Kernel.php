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
         Commands\DataSync::class,
         Commands\PayrollSync::class,
         Commands\BudgetSync::class,
         Commands\TaskcodesSync::class,
         Commands\DealSync::class,
         Commands\ScheduledReports::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

         //Job Sync
         $schedule->command('datasync 1')->dailyAt('23:15')->timezone('America/New_York');
         $schedule->command('datasync 2')->dailyAt('23:00')->timezone('America/New_York');
         $schedule->command('datasync 3')->dailyAt('23:10')->timezone('America/New_York');
         $schedule->command('datasync 4')->dailyAt('23:30')->timezone('America/New_York');
         $schedule->command('datasync 8')->dailyAt('22:10')->timezone('America/New_York');
         $schedule->command('datasync 9')->dailyAt('23:50')->timezone('America/New_York');
         $schedule->command('datasync 11')->dailyAt('22:00')->timezone('America/New_York');
         $schedule->command('datasync 12')->dailyAt('23:10')->timezone('America/New_York');
         

         $schedule->command('datasync 19')->twiceDaily(4, 23)->timezone('America/New_York');
         $schedule->command('datasync 20')->dailyAt('23:45')->timezone('America/New_York');
         

         $schedule->command('datasync 13')->dailyAt('22:10')->timezone('America/New_York');
         $schedule->command('datasync 14')->dailyAt('22:15')->timezone('America/New_York');
         $schedule->command('datasync 15')->dailyAt('22:20')->timezone('America/New_York');

         //$schedule->command('payrollsync 16')->dailyAt('22:25')->timezone('America/New_York');
         //$schedule->command('payrollsync 17')->dailyAt('22:25')->timezone('America/New_York');

         $schedule->command('taskcodessync 23')->dailyAt('23:20')->timezone('America/New_York');
         $schedule->command('taskcodessync 24')->dailyAt('23:22')->timezone('America/New_York');
         $schedule->command('taskcodessync 25')->dailyAt('23:25')->timezone('America/New_York');

         $schedule->command('budgetsync 18')->twiceDaily(4, 23)->timezone('America/New_York');
         $schedule->command('budgetsync 21')->twiceDaily(4, 23)->timezone('America/New_York');
         $schedule->command('budgetsync 22')->twiceDaily(4, 23)->timezone('America/New_York');
         $schedule->command('budgetsync 26')->twiceDaily(4, 23)->timezone('America/New_York');
         $schedule->command('budgetsync 27')->twiceDaily(4, 23)->timezone('America/New_York');

         $schedule->command('dealsync 50')->hourly()->timezone('America/New_York');
         $schedule->command('dealsync 51')->hourly()->timezone('America/New_York');
         
         
         //$schedule->command('payrollsync 30')->everyFiveMinutes()->timezone('America/New_York');
         //$schedule->command('datasync 2')->everyMinute()->timezone('America/New_York');
         
    }
}
