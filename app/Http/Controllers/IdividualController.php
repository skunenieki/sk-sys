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
        $start     = $request->get('start', false);
        $number    = $request->get('number', false);
        $birthYear = $request->get('birthYear', false);
        $eventYear = $request->get('eventYear', false);

        $result = Individual::with('teams');

        if (false !== $name) {
            $result->where('name', $name);
        }

        if (false !== $eventYear) {
            $result->whereIn('eventYear', explode(',', $eventYear));
        }

        if (false !== $birthYear) {
            $result->whereIn('birthYear', explode(',', $birthYear));
        }

        if (false !== $number) {
            $result->whereIn('number', explode(',', $number));
        }

        if (false !== $start) {
            // dd(explode(',', $start));
            $result->whereIn('start', explode(',', $start));
        }

        // dd($result->toSql());

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

        // \Event::listen('illuminate.query', function($query, $params, $time, $conn)
        // {
        //     dd(array($query, $params, $time, $conn));
        // });

        $result = $result->get();

        $result->map(function($item) {
            $item->result = $item->result;
        });

        return $result;
    }

    public function show($id)
    {
        $individual = Individual::with('teams')->findOrFail($id);

        $individual->result = $individual->result;

        return $individual;
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
                         ->where('eventYear', $request->eventYear)
                         ->first();

        if (null !== $ind) {
            return response(['error' => ['field' => 'number', 'msg' => 'Number already registered']], 400);
        }

        $ind = Individual::where('name', $request->name)
                         ->where('birthYear', $request->birthYear)
                         ->where('eventYear', $request->eventYear)
                         ->first();

        if (null !== $ind && false === $request->acceptExisting) {
            return response(['error' => 'Participant with such name and birth year exists, need approval to proceed'], 400);
        }


        $participant = Participant::where('name', $request->name)
                                  ->where('birthYear', $request->birthYear)
                                  ->first();

        if (null === $participant) {
            $participant = new Participant;
            $participant->name      = $request->name;
            $participant->gender    = $request->gender;
            $participant->birthYear = $request->birthYear;
            $participant->save();
        }

        $ind = new Individual;
        $ind->number        = $request->number;
        $ind->name          = $request->name;
        $ind->bikeType      = null;
        $ind->birthYear     = $request->birthYear;
        $ind->gender        = $request->gender;
        $ind->eventYear     = $request->eventYear;
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

    public function update(Request $request, $id)
    {
        $requestTeams = $request->input('teams', false);
        $teams = [];
        if (false !== $requestTeams) {
            foreach ($requestTeams as $team) {
                $team = Team::firstOrCreate(['name' => $team['name']]);
                $teams[] = $team->id;
            }
        }

        if ($request->start === '') {
            $request->start = null;
        }

        if ($request->turn === '') {
            $request->turn = null;
        }

        if ($request->finish === '') {
            $request->finish = null;
        }

        $individual            = Individual::find($id);
        $individual->number    = $request->number;
        $individual->name      = $request->name;
        $individual->birthYear = $request->birthYear;
        $individual->gender    = $request->gender;
        $individual->bikeType  = $request->bikeType;

        $individual->start     = $request->start;
        $individual->turn      = $request->turn;
        $individual->finish    = $request->finish;
        $individual->penalty   = $request->penalty;

        $individual->comment   = $request->comment;
        $individual->teams()->sync($teams);
        $individual->save();

        $individual->teams = $individual->teams;
        $individual->group = $individual->group;

        return $individual;
    }

    public function years()
    {
        return Individual::select('eventYear')
                         ->distinct()
                         ->orderBy('eventYear', 'desc')
                         ->get();
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
