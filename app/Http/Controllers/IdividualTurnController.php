<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\IndividualTurn;

class IdividualTurnController extends Controller
{
    public function index()
    {
        $turns = IndividualTurn::where('eventYear', 2020) // @todo 2020
                             ->orderBy('slot', 'desc')
                             ->take(20)
                             ->get();

        $count = IndividualTurn::where('eventYear', 2020)->count(); // @todo 2020

        foreach ($turns as $turn) {
            $turn->sequence = $count--;
        }

        return $turns;
    }

    public function store(Request $request)
    {
        $number = $request->input('number', null);

        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $turn = new IndividualTurn;
        $turn->number    = $number;
        $turn->turn      = Carbon::now()->diff(new Carbon($activeEvent->settings['startDate']))->format('%H:%I:%S');
        $turn->slot      = IndividualTurn::where('eventYear', 2020)->count() + 1; // @todo 2020
        $turn->manual    = $request->input('manual', false);
        $turn->eventYear = 2020; // @todo 2020
        $turn->save();

        if ($number !== null) {
            $individual = Individual::where('eventYear', 2020)->where('number', $number)->first(); // @todo 2020
            if ($individual->turn === null || ($individual->turn !== null && $turn->manual === true)) {
                $individual->turn = $turn->turn;
            }
            $individual->save();
        }

        $count = IndividualTurn::where('eventYear', 2020)->count(); // @todo 2020

        $turn->sequence = $count;

        return $turn;
    }

    public function destroy($id)
    {
        IndividualTurn::destroy($id);
        return;
    }
}
