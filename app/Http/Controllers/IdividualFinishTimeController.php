<?php

namespace Skunenieki\System\Http\Controllers;

// use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;
use Skunenieki\System\Events\UpdateFinish;
use Skunenieki\System\Models\IndividualFinishTime;

class IdividualFinishTimeController extends Controller
{
    public function index()
    {
        return IndividualFinishTime::where('eventYear', 2016)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $finishTime            = new IndividualFinishTime;
        $finishTime->finish    = $request->input('time');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2016;
        $finishTime->save();

	// LaravelEvent::fire(new UpdateFinish($finishTime->eventYear));

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime = IndividualFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

	// LaravelEvent::fire(new UpdateFinish($finishTime->eventYear));

        return $finishTime;
    }

    public function destroy($id)
    {
        $finishTime = IndividualFinishTime::find($id);
        $eventYear = $finishTime->eventYear;
        $finishTime->delete();

	// LaravelEvent::fire(new UpdateFinish($eventYear));
        return;
    }
}
