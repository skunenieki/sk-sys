<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
// use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateTriathlonFinish;
use Skunenieki\System\Models\TriathlonFinishNumber;

class TriathlonFinishNumberController extends Controller
{
    public function index()
    {
        return TriathlonFinishNumber::where('eventYear', 2017)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $finishNumber            = new TriathlonFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2017;
        $finishNumber->save();

	// LaravelEvent::fire(new UpdateTriathlonFinish($finishNumber->eventYear));

        return $finishNumber;
    }

    public function destroy($id)
    {
        $finishNumber = TriathlonFinishNumber::find($id);
        $eventYear = $finishNumber->eventYear;
        $finishNumber->delete();

	// LaravelEvent::fire(new UpdateFinish($eventYear));
        return;
    }
}
