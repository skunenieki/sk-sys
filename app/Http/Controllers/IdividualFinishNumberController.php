<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\IndividualFinishNumber;
use Skunenieki\System\Models\IndividualFinishTime;

class IdividualFinishNumberController extends Controller
{
    public function index()
    {
        return IndividualFinishNumber::where('eventYear', 2015)
                                   ->orderBy('slot', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        // TODO: Change start date so that it comes from settings
        $finishNumber            = new IndividualFinishNumber;
        $finishNumber->slot      = IndividualFinishNumber::where('eventYear', 2015)->count() + 1;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2015;
        $finishNumber->save();

        if ($request->number !== null) {
            $finishTime = IndividualFinishTime::where('eventYear', 2015)
                                              ->where('slot', $finishNumber->slot)
                                              ->first();
            $individual = Individual::where('eventYear', 2015)
                                    ->where('number', $request->number)
                                    ->first();

            $individual->finish = $finishTime->finish;
            $individual->save();
        }

        return $finishNumber;
    }
}
