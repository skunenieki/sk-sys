<?php

namespace Skunenieki\System\Http\Controllers;

use Skunenieki\System\Models\Event;
use Skunenieki\System\Models\Option;

class MainViewController extends Controller
{
    public function render()
    {

        $activeEvent = Event::where('eventYear', Option::where('key', 'activeEventYear')->first()['value'])->first();

        $settings = [
            'indActiveEventStart' => null,
        ];
        if (null !== $activeEvent) {
            $settings['indActiveEventStart'] = $activeEvent->settings['startDate'] ?: null;
        }

        return view('index', $settings);
    }
}
