<?php

namespace Skunenieki\System\Http\Controllers;

// use Event as LaravelEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateFinish;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\IndividualFinishTime;
use Skunenieki\System\Models\Option;

class IdividualFinishTimeController extends Controller
{
    public function index()
    {
        return IndividualFinishTime::where('eventYear', 2018)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new IndividualFinishTime;
        // $finishTime->finish = $request->input('time');
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['startDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2018;
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
