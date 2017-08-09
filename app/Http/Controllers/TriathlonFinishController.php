<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Triathlon;
use Skunenieki\System\Models\TriathlonTeam;

class TriathlonFinishController extends Controller
{
    public function finish(Request $request)
    {
        $ind = Triathlon::where('eventYear', 2017)
                  ->whereNull('finish')
                  ->get();

        $team = TriathlonTeam::where('eventYear', 2017)
                  ->whereNull('finish')
                  ->get();

        $result = [];
        foreach ($ind as $participant) {
            $result[$participant->number] = $participant;
        }

        foreach ($team as $participant) {
            $result[$participant->number] = $participant;
        }

        $result = array_sort_recursive($result);

        $ret = [];
        foreach ($result as $value) {
            $ret[] = $value;
        }

        return $ret;
    }
}
