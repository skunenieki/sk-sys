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
        return IndividualFinishNumber::where('eventYear', 2016)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $finishNumber            = new IndividualFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2016;
        $finishNumber->save();

	// LaravelEvent::fire(new UpdateFinish($finishNumber->eventYear));

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
