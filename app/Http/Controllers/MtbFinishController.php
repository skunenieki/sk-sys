<?php

// TODO: Rename to provisory controller

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Mtb;

class MtbFinishController extends Controller
{
    public function finish(Request $request)
    {
        $mtb = Mtb::where('eventYear', 2015)
                  ->whereNull('finish')
                  ->get();

        $numbers = [];
        foreach ($mtb as $participant) {
            $numbers[$participant->lapsDone][$participant->genderWeight][] = $participant;
        }

        $numbers = array_sort_recursive($numbers);

        $expectedFinish = [];
        foreach ($numbers as $lapsdone) {
            foreach ($lapsdone as $gender) {
                foreach ($gender as $participant) {
                    $expectedFinish[] = $participant;
                }
            }
        }

        return $expectedFinish;
    }
}
