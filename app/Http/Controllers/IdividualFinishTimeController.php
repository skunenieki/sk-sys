<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\IndividualFinishTime;

class IdividualFinishTimeController extends Controller
{
    public function index()
    {
        return IndividualFinishTime::where('eventYear', 2015)
                                   ->orderBy('slot', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        // TODO: Change start date so that it comes from settings
        $finishTime            = new IndividualFinishTime;
        $finishTime->slot      = IndividualFinishTime::where('eventYear', 2015)->count() + 1;
        $finishTime->finish    = Carbon::now()->diff(new Carbon('2015-07-14T20:00:00+03:00'))->format('%H:%I:%S');
        $finishTime->disabled  = false;
        $finishTime->eventYear = 2015;
        $finishTime->save();

        return $finishTime;
    }

    public function update(Request $request, $id)
    {
        $finishTime = IndividualFinishTime::find($id);
        $finishTime->disabled = $request->disabled;
        $finishTime->save();

        return $finishTime;
    }
}
