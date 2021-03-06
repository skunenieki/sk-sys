<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;
use Skunenieki\System\Models\TriathlonFinishTime;
use Skunenieki\System\Events\UpdateTriathlonFinish;

class TriathlonFinishTimeController extends Controller
{
    public function index()
    {
        $finishTimes = TriathlonFinishTime::where('eventYear', 2020) // @todo 2020
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();

        $count = TriathlonFinishTime::where('eventYear', 2020)->count(); // @todo 2020

        foreach ($finishTimes as $finishTime) {
            $finishTime->sequence = $count--;
        }

        return $finishTimes;
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new TriathlonFinishTime;
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['triathlonStartDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2020; // @todo 2020
        $finishTime->save();

        $count = TriathlonFinishTime::where('eventYear', 2020)->count(); // @todo 2020

        $finishTime->sequence = $count;

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

        return;
    }
}
