<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
// use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;
use Skunenieki\System\Models\TriathlonFinishTime;
use Skunenieki\System\Events\UpdateTriathlonFinish;

class TriathlonFinishTimeController extends Controller
{
    public function index()
    {
        return TriathlonFinishTime::where('eventYear', 2018)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new TriathlonFinishTime;
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['triathlonStartDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2018;
        $finishTime->save();

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime = TriathlonFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

        return $finishTime;
    }

    public function destroy($id)
    {
        $finishTime = TriathlonFinishTime::find($id);
        $eventYear = $finishTime->eventYear;
        $finishTime->delete();

	// LaravelEvent::fire(new UpdateTriathlonFinish($eventYear));
        return;
    }
}
