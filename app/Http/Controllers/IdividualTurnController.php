<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\IndividualTurn;

class IdividualTurnController extends Controller
{
    public function index()
    {
        return IndividualTurn::where('eventYear', 2015)
                             ->orderBy('slot', 'desc')
                             ->take(20)
                             ->get();
    }

    public function store(Request $request)
    {
        $number = $request->input('number', null);

        // TODO: Change start date so that it comes from settings
        $turn = new IndividualTurn;
        $turn->number    = $number;
        $turn->turn      = Carbon::now()->diff(new Carbon('2015-07-14T20:00:00+03:00'))->format('%H:%I:%S');
        $turn->slot      = IndividualTurn::where('eventYear', 2015)->count() + 1;
        $turn->manual    = $request->input('manual', false);
        $turn->eventYear = 2015;
        $turn->save();

        if ($number !== null) {
            $individual = Individual::where('eventYear', 2015)->where('number', $number)->first();
            if ($individual->turn === null || ($individual->turn !== null && $turn->manual === true)) {
                $individual->turn = $turn->turn;
            }
            $individual->save();
        }

        return $turn;
    }

    public function destroy($id)
    {
        IndividualTurn::destroy($id);
        return;
    }
}
