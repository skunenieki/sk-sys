<?php

// TODO: Rename to provisory controller

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;

class IdividualFinishController extends Controller
{
    public function finish(Request $request)
    {
        $individual = Individual::where('eventYear', 2015)
                                ->whereNotNull('start')
                                ->whereNull('turn')
                                ->orderBy('start', 'asc')
                                ->get();

        $individual->map(function($item) {
            $item->startInSeconds = $item->startInSeconds;
        });

        return $individual;
    }
}
