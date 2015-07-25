<?php

namespace Skunenieki\System\Http\Controllers;

use Skunenieki\System\Models\IndividualFinishTime;
use Skunenieki\System\Models\IndividualFinishNumber;

class TestController extends Controller
{
    public function test()
    {
        $numbers = IndividualFinishNumber::where('eventYear', '2015')->orderBy('id', 'asc')->get();

        $times = IndividualFinishTime::where('eventYear', 2015)->where('disabled', false)->orderBy('id', 'asc')->get();

        $matches = [];
        foreach ($times as $order => $time) {
            if (true === isset($numbers[$order])) {
                $matches[] = [
                    'number' => $numbers[$order]->number,
                    'finish'   => $time->finish,
                ];
            } else {
                $matches[] = [
                    'number' => false,
                    'finish'   => $time->finish,
                ];
            }
        }



        return $matches;
    }
}
