<?php

// TODO: Rename to provisory controller

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class IdividualFinishController extends Controller
{
    public function turn(Request $request)
    {
        $individual = Individual::where('eventYear', 2017)
                                ->whereNotNull('start')
                                ->whereNull('turn')
                                ->orderBy('start', 'asc')
                                ->get();

        $individual->map(function($item) {
            $item->startInSeconds = $item->startInSeconds;
        });

        return $individual;
    }

    public function finish(Request $request)
    {
        $individual = Individual::where('eventYear', 2017)
                                ->whereNotNull('start')
                                ->whereNotNull('turn')
                                ->whereNull('finish')
                                ->get();

        $individual->map(function($item) {
            $item->tempResultInSeconds = $item->turnInSeconds - $item->startInSeconds;

            if ($item->tempResultInSeconds < 0) {
                $item->tempResultInSeconds = 0;
            }

            $item->tempResult = (new Carbon)->diff((new Carbon)->addSeconds($item->tempResultInSeconds))->format('%H:%I:%S');
        });

        $individual = $individual->toArray();

        usort($individual, function($a, $b) {
            return $a['tempResultInSeconds'] - $b['tempResultInSeconds'];
        });

        return $individual;
    }
}
