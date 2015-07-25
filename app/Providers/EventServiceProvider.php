<?php

namespace Skunenieki\System\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Skunenieki\System\Events\UpdateFinish' => [
            'Skunenieki\System\Listeners\UpdateFinishListener',
        ],
    ];
}
