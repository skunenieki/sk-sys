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
            'indActiveEventStart'       => null,
            'mtbActiveEventStart'       => null,
            'triathlonActiveEventStart' => null,
        ];

        if (null !== $activeEvent) {
            $settings['indActiveEventStart'] = isset($activeEvent->settings['startDate']) ? $activeEvent->settings['startDate'] : null;
        }

        if (null !== $activeEvent) {
            $settings['mtbActiveEventStart'] = isset($activeEvent->settings['mtbStartDate']) ? $activeEvent->settings['mtbStartDate'] : null;
        }

        if (null !== $activeEvent) {
            $settings['triathlonActiveEventStart'] = isset($activeEvent->settings['triathlonStartDate']) ? $activeEvent->settings['triathlonStartDate'] : null;
        }

        return view('index', $settings);
    }
}
