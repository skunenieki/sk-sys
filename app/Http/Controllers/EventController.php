<?php

namespace Skunenieki\System\Http\Controllers;

use Illuminate\Http\Request;
use Skunenieki\System\Models\Event;

class EventController extends Controller
{
    public function index(Request $request)
    {
        return Event::orderBy('eventYear', 'desc')->get();
    }

    public function show($eventYear)
    {
        return Event::where('eventYear', $eventYear)->first();
    }

    public function store(Request $request)
    {
        $event = new Event;
        $event->eventYear = $request->eventYear;
        $event->save();
        $event->eventYear = $request->eventYear;

        return $event;
    }

    public function update(Request $request, $eventYear)
    {
        $event = Event::find($eventYear);

        $settings = $event->settings;
        foreach ($request->all() as $key => $value) {
            if (in_array($key, ['eventYear', 'created_at', 'updated_at', 'settings'])) {
                continue;
            }

            $settings[$key] = $value;
        }

        $event->settings = $settings;
        $event->save();

        return $event;
    }

    public function destroy($eventYear)
    {
        $event = Event::where('eventYear', $eventYear)->first()->delete();
        return;
    }
}
