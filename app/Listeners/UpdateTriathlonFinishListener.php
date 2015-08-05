<?php

namespace Skunenieki\System\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Skunenieki\System\Models\Triathlon;
use Skunenieki\System\Models\TriathlonTeam;
use Skunenieki\System\Models\TriathlonFinishTime;
use Skunenieki\System\Models\TriathlonFinishNumber;
use Skunenieki\System\Events\UpdateTriathlonFinish;

class UpdateTriathlonFinishListener
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
    public function handle(UpdateTriathlonFinish $event)
    {
        $numbers = TriathlonFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
        $times   = TriathlonFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

        $matches = [];
        foreach ($times as $order => $time) {
            if (true === isset($numbers[$order])) {
                $matches[] = [
                    'number' => $numbers[$order]->number,
                    'finish'   => $time->finish,
                ];

                $tri = Triathlon::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();

                if (null === $tri) {
                    $tri = TriathlonTeam::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                }

                if (null !== $tri) {
                    $tri->finish = $time->finish;
                    $tri->save();
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
