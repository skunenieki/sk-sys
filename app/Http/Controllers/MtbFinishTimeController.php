<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateMtbFinish;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\MtbFinishTime;
use Skunenieki\System\Models\Option;

class MtbFinishTimeController extends Controller
{
    public function index()
    {
        return MtbFinishTime::where('eventYear', 2018)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new MtbFinishTime;
        $finishTime->finish    = $request->input('time');
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['mtbStartDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2018;
        $finishTime->save();

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime = MtbFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

	// LaravelEvent::fire(new UpdateMtbFinish($finishTime->eventYear));

        return $finishTime;
    }

    public function destroy($id)
    {
        $finishTime = MtbFinishTime::find($id);
        $eventYear = $finishTime->eventYear;
        $finishTime->delete();

	// LaravelEvent::fire(new UpdateMtbFinish($eventYear));
        return;
    }
}
