<?php

// TODO: Rename to provisory controller

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Mtb;

class MtbFinishController extends Controller
{
    // public function finish(Request $request)
    // {
    //     $mtb = Mtb::where('eventYear', 2019) // @todo 2019
    //               ->whereNull('finish')
    //               ->orderBy('number')
    //               ->get();

    //     return $mtb;

    //     // $numbers = [];
    //     // foreach ($mtb as $participant) {
    //     //     $numbers[] = $participant;
    //     // }

    //     // // $numbers = array_sort_recursive($numbers);

    //     // $expectedFinish = [];
    //     // foreach ($numbers as $lapsdone) {
    //     //     foreach ($lapsdone as $gender) {
    //     //         foreach ($gender as $participant) {
    //     //             $expectedFinish[] = $participant;
    //     //         }
    //     //     }
    //     // }

    //     // return $expectedFinish;
    // }
}
