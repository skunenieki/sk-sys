<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Team;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\Participant;

class IdividualController extends Controller
{
    public function index(Request $request)
    {
        $skip      = $request->get('offset', 0);
        $take      = $request->get('limit', 10);
        $sort      = $request->get('sort', false);
        $name      = $request->get('name', false);
        $number    = $request->get('number', false);
        $birthYear = $request->get('birthYear', false);
        $eventYear = $request->get('eventYear', false);

        $result = Individual::with('teams');

        if (false !== $name) {
            $result->where('name', $name);
        }

        if (false !== $eventYear) {
            $result->whereBetween('eventYear', [new Carbon($eventYear.'-01-01'), new Carbon(($eventYear+1).'-01-01')]);
        }

        if (false !== $birthYear) {
            $result->whereBetween('birthYear', [new Carbon($birthYear.'-01-01'), new Carbon(($birthYear+1).'-01-01')]);
        }

        if (false !== $number) {
            $result->where('number', $number);
        }

        $result->skip($skip)->take($take);

        if (false !== $sort) {
            foreach (explode(',', $sort) as $sortBy) {
                if (substr($sortBy, 0, 1) === '-') {
                    $result->orderBy(ltrim($sortBy, '-'), 'desc');
                } else {
                    $result->orderBy($sortBy, 'asc');
                }
            }
        }


        return $result->get();
    }

    public function show($id)
    {
        return Individual::find($id);
    }

    public function store(Request $request)
    {
        if (false !== empty($request->teams)) {
            $teams = [];
            foreach ($request->teams as $team) {
                $team = Team::firstOrCreate(['name' => $team['name']]);
                $teams[] = $team->id;
            }
        }

        $ind = Individual::where('number', $request->number)
                         ->whereBetween('eventYear', [new Carbon($request->eventYear.'-01-01'), new Carbon(($request->eventYear+1).'-01-01')])
                         ->first();

        if (null !== $ind) {
            return response(['error' => ['field' => 'number', 'msg' => 'Number already registered']], 400);
        }

        $ind = Individual::where('name', $request->name)
                         ->whereBetween('birthYear', [new Carbon($request->birthYear.'-01-01'), new Carbon(($request->birthYear+1).'-01-01')])
                         ->whereBetween('eventYear', [new Carbon($request->eventYear.'-01-01'), new Carbon(($request->eventYear+1).'-01-01')])
                         ->first();

        if (null !== $ind && false === $request->acceptExisting) {
            return response(['error' => 'Participant with such name and birth year exists, need approval to proceed'], 400);
        }


        $participant = Participant::where('name', $request->name)
                                  ->whereBetween('birthYear', [new Carbon($request->birthYear.'-01-01'), new Carbon(($request->birthYear+1).'-01-01')])
                                  ->first();

        if (null === $participant) {
            $participant = new Participant;
            $participant->name      = $request->name;
            $participant->gender    = $request->gender;
            $participant->birthYear = new Carbon($request->birthYear.'-01-01');
            $participant->save();
        }

        $ind = new Individual;
        $ind->number        = $request->number;
        $ind->name          = $request->name;
        $ind->birthYear     = new Carbon($request->birthYear.'-01-01');
        $ind->gender        = $request->gender;
        $ind->eventYear     = new Carbon('august '.$request->eventYear.' second sunday 9:00 AM');
        $ind->comment       = $request->comment;
        $ind->participantId = $participant->id;
        $ind->save();
        $ind->teams()->sync($teams);
        $ind->save();

        return $ind;
    }

    public function destroy($id)
    {
        Individual::destroy($id);
        return;
    }

    public function statistics()
    {
        return [
            'total' => 5,
            'V'     => 1,
            'S'     => 4,
        ];
    }
}
