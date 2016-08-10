<?php

namespace Skunenieki\System\Http\Controllers;

use Skunenieki\System\Models\Mtb;
use Skunenieki\System\Models\MtbFinishTime;
use Skunenieki\System\Models\MtbFinishNumber;

class TestController extends Controller
{
    public function test()
    {
        $event = new \StdClass;
        $event->eventYear = 2016;

        $numbers = MtbFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
        $times   = MtbFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

        $matches  = [];
        $lapCount = [];
        foreach ($times as $order => $time) {
            if (true === isset($numbers[$order])) {
                if (false === isset($lapCount[$numbers[$order]->number])) {
                    $lapCount[$numbers[$order]->number] = 1;
                } else {
                    $lapCount[$numbers[$order]->number]++;
                }

                $matches[] = [
                    'number' => $numbers[$order]->number,
                    'finish' => $time->finish,
                    'lap'    => $lapCount[$numbers[$order]->number],
                ];

                // // $mtb = Mtb::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                // if (null !== $mtb) {
                //     if ($lapCount[$numbers[$order]->number] < $mtb->laps) {
                //         $varName = "lap{$lapCount[$numbers[$order]->number]}";
                //         $mtb->{$varName} = $time->finish;
                //     } else {
                //         $mtb->finish = $time->finish;
                //     }
                //     $mtb->save();
                // }
            } else {
                $matches[] = [
                    'number' => false,
                    'finish' => $time->finish,
                ];
            }
        }



        return $matches;
    }
}
