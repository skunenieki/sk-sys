<?php

namespace Skunenieki\System\Http\Controllers;

use Event as LaravelEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Events\UpdateFinish;
use Skunenieki\System\Models\IndividualFinishTime;
use Skunenieki\System\Models\IndividualFinishNumber;

class IdividualFinishNumberController extends Controller
{
    public function index()
    {
        return IndividualFinishNumber::where('eventYear', 2015)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        // TODO: Change start date so that it comes from settings
        $finishNumber            = new IndividualFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2015;
        $finishNumber->save();

        LaravelEvent::fire(new UpdateFinish($finishNumber->eventYear));

        return $finishNumber;
    }

    public function destroy($id)
    {
        $finishNumber = IndividualFinishNumber::find($id);
        $eventYear = $finishNumber->eventYear;
        $finishNumber->delete();

        LaravelEvent::fire(new UpdateFinish($eventYear));
        return;
    }
}
