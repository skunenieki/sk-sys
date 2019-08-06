<?php

namespace Skunenieki\System\Http\Controllers;

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
        $times = [];

        $finishTimes = IndividualFinishTime::where('eventYear', 2019) // @todo 2019
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();

        $count = IndividualFinishTime::where('eventYear', 2019)->count(); // @todo 2019

        foreach ($finishTimes as $finishTime) {
            $finishTime->sequence = $count--;
            $times[] = $finishTime;
        }

        return $times;
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new IndividualFinishTime;
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['startDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2019; // @todo 2019
        $finishTime->save();

        $count = IndividualFinishTime::where('eventYear', 2019)->count(); // @todo 2019

        $finishTime->sequence = $count;

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime = IndividualFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

        return $finishTime;
    }

    public function destroy($id)
    {
        $finishTime = IndividualFinishTime::find($id);
        $eventYear = $finishTime->eventYear;
        $finishTime->delete();

        return;
    }
}
