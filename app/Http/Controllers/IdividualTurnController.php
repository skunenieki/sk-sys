<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\IndividualTurn;

class IdividualTurnController extends Controller
{
    public function store(Request $request)
    {
        $id     = $request->input('id', null);
        $number = $request->input('number', null);

        $turnTime = Carbon::now()->diff(new Carbon('2015-07-14T20:00:00+03:00'))->format('%H:%I:%S');


        // TODO: Change start date so that it comes from settings
        $turn = new IndividualTurn;
        $turn->number    = $number;
        $turn->turn      = $turnTime;
        $turn->slot      = IndividualTurn::where('eventYear', 2015)->count() + 1;
        $turn->eventYear = 2015;
        $turn->save();

        if ($number !== null && $id !== null) {
            $individual = Individual::find($id);
            $individual->turn = $turnTime;
            $individual->save();
        }

        return $turn;
    }
}
