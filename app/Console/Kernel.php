<?php

namespace Skunenieki\System\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Skunenieki\System\Console\Commands\GetDBSnapshot::class,
        \Skunenieki\System\Console\Commands\SyncWithAlgolia::class,
        \Skunenieki\System\Console\Commands\ProcessIndividual::class,
        \Skunenieki\System\Console\Commands\ProcessTriathlon::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
