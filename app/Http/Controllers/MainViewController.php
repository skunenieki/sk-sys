<?php

namespace Skunenieki\System\Http\Controllers;

use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;

class MainViewController extends Controller
{
    public function render()
    {

        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        return view('index', [
            'indActiveEventStart' => $activeEvent->settings['startDate'],
        ]);
    }
}
