<?php

namespace Skunenieki\System\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Skunenieki\System\Console\Commands\AlgoliaRebuild;
use Skunenieki\System\Console\Commands\AlgoliaUpdate;
use Skunenieki\System\Console\Commands\GetDBSnapshot;
use Skunenieki\System\Console\Commands\ProcessIndividual;
use Skunenieki\System\Console\Commands\ProcessMtb;
use Skunenieki\System\Console\Commands\ProcessTriathlon;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AlgoliaRebuild::class,
        AlgoliaUpdate::class,
        GetDBSnapshot::class,
        ProcessIndividual::class,
        ProcessMtb::class,
        ProcessTriathlon::class,
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
