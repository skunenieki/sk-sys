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
        $finishTimes = MtbFinishTime::where('eventYear', 2020) // @todo 2020
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();

        $count = MtbFinishTime::where('eventYear', 2020)->count(); // @todo 2020

        foreach ($finishTimes as $finishTime) {
            $finishTime->sequence = $count--;
        }

        return $finishTimes;
    }

    public function store(Request $request)
    {
        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $finishTime            = new MtbFinishTime;
        $finishTime->finish    = Carbon::now()->diff(new Carbon($activeEvent->settings['mtbStartDate']))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2020; // @todo 2020
        $finishTime->save();

        $count = MtbFinishTime::where('eventYear', 2020)->count(); // @todo 2020

        $finishTime->sequence = $count;

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime           = MtbFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

        return $finishTime;
    }

    public function destroy($id)
    {
        $finishTime = MtbFinishTime::find($id);
        $eventYear  = $finishTime->eventYear;
        $finishTime->delete();

        return;
    }
}
