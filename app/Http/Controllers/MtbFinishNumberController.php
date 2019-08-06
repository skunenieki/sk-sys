<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
// use Event as LaravelEvent;
use Illuminate\Http\Request;
use Skunenieki\System\Events\UpdateMtbFinish;
use Skunenieki\System\Models\MtbFinishNumber;

class MtbFinishNumberController extends Controller
{
    public function index()
    {
        $numbers = MtbFinishNumber::where('eventYear', 2019) // @todo 2019
                                   ->orderBy('id', 'desc')
                                   ->take(30)
                                   ->get();


        $count = MtbFinishNumber::where('eventYear', 2019)->count(); // @todo 2019

        foreach ($numbers as $number) {
            $number->sequence = $count--;
        }

        return $numbers;
    }

    public function store(Request $request)
    {
        $finishNumber            = new MtbFinishNumber;
        $finishNumber->number    = $request->number;
        $finishNumber->manual    = $request->manual;
        $finishNumber->eventYear = 2019; // @todo 2019
        $finishNumber->save();

        $count = MtbFinishNumber::where('eventYear', 2019)->count(); // @todo 2019

        $finishNumber->sequence = $count;

        return $finishNumber;
    }

    public function destroy($id)
    {
        $finishNumber = MtbFinishNumber::find($id);
        $eventYear = $finishNumber->eventYear;
        $finishNumber->delete();

        return;
    }
}
