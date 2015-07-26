<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateMtbFinish;
use Skunenieki\System\Models\MtbFinishNumber;

class MtbFinishNumberController extends Controller
{
    public function index()
    {
        return MtbFinishNumber::where('eventYear', 2015)
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();
    }

    public function store(Request $request)
    {
        $finishNumber            = new MtbFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2015;
        $finishNumber->save();

        LaravelEvent::fire(new UpdateMtbFinish($finishNumber->eventYear));

        return $finishNumber;
    }

    public function destroy($id)
    {
        $finishNumber = MtbFinishNumber::find($id);
        $eventYear = $finishNumber->eventYear;
        $finishNumber->delete();

        LaravelEvent::fire(new UpdateFinish($eventYear));
        return;
    }
}
