<?php

namespace Skunenieki\System\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Events\UpdateFinish;
use Skunenieki\System\Models\IndividualFinishTime;
use Skunenieki\System\Models\IndividualFinishNumber;

class UpdateFinishListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle(UpdateFinish $event)
    {
        $numbers = IndividualFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
        $times   = IndividualFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

        $matches = [];
        foreach ($times as $order => $time) {
            if (true === isset($numbers[$order])) {
                $matches[] = [
                    'number' => $numbers[$order]->number,
                    'finish'   => $time->finish,
                ];

                $ind = Individual::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                if (null !== $ind) {
                    $ind->finish = $time->finish;
                    $ind->save();
                }
            } else {
                $matches[] = [
                    'number' => false,
                    'finish'   => $time->finish,
                ];
            }
        }
    }
}
