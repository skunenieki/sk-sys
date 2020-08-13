<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
// use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateFinish;
use Skunenieki\System\Models\IndividualFinishNumber;

class IdividualFinishNumberController extends Controller
{
    public function index()
    {
        $numbers = IndividualFinishNumber::where('eventYear', 2020) // @todo 2020
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();

        $count = IndividualFinishNumber::where('eventYear', 2020)->count(); // @todo 2020

        foreach ($numbers as $number) {
            $number->sequence = $count--;
        }

        return $numbers;
    }

    public function store(Request $request)
    {
        $finishNumber            = new IndividualFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2020; // @todo 2020
        $finishNumber->save();

        $count = IndividualFinishNumber::where('eventYear', 2020)->count(); // @todo 2020

        $finishNumber->sequence = $count;

        return $finishNumber;
    }

    public function destroy($id)
    {
        $finishNumber = IndividualFinishNumber::find($id);
        $eventYear = $finishNumber->eventYear;
        $finishNumber->delete();

	// LaravelEvent::fire(new UpdateFinish($eventYear));
        return;
    }
}
