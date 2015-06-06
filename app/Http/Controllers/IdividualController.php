<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Team;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\Participant;

class IdividualController extends Controller
{
    private $groups = false;

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

        $result = $result->get();

        $result->map(function($item) {
            if (null !== $item->start && null !== $item->finish) {
                $item->result = $this->calculateResult($item->start, $item->finish, $item->penalty);
            } else {
                $item->result = null;
            }

            if (null != $item->gender) {
                $item->group = $this->calculateGroup($item);
            } else {
                $item->group = null;
            }
        });

        return $result;
    }

    public function show($id)
    {
        $individual = Individual::with('teams')->findOrFail($id);

        if (null !== $individual->start && null !== $individual->finish) {
            $individual->result = $this->calculateResult($individual->start, $individual->finish, $individual->penalty);
        } else {
            $individual->result = null;
        }

        $individual->group = $this->calculateGroup($individual);

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

    public function update(Request $request, $id)
    {
        if (false !== empty($request->teams)) {
            $teams = [];
            foreach ($request->teams as $team) {
                $team = Team::firstOrCreate(['name' => $team['name']]);
                $teams[] = $team->id;
            }
        }

        $individual            = Individual::find($id);
        $individual->number    = $request->number;
        $individual->name      = $request->name;
        $individual->birthYear = new Carbon($request->birthYear.'-01-01');
        $individual->gender    = $request->gender;
        $individual->bikeType  = $request->bikeType;

        $individual->start     = $request->start;
        $individual->turn      = $request->turn;
        $individual->finish    = $request->finish;
        $individual->penalty   = $request->penalty;

        $individual->comment   = $request->comment;
        $individual->teams()->sync($teams);
        $individual->save();

        if (null !== $individual->start && null !== $individual->finish) {
            $individual->result = $this->calculateResult($individual->start, $individual->finish, $individual->penalty);
        } else {
            $individual->result = null;
        }

        $individual->group = $this->calculateGroup($individual);

        return $individual;
    }

    public function statistics()
    {
        return [
            'total' => 5,
            'V'     => 1,
            'S'     => 4,
        ];
    }

    protected function calculateResult($start, $finish, $penalty) {
        return (new Carbon($start))->diff(
                (new Carbon($finish))->addSeconds(
                    (new Carbon($penalty))->diffInSeconds(new Carbon('0:00:00')))
            )->format('%H:%I:%S');
    }

    protected function calculateGroup($item) {
        $yearRanges = [
            '1996-1996' => [
                '0-100' => ['SV' => 'S1'],
            ],
            '1997-2015' => [
                '0-8'    => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '9-12'   => ['CV' => 'TV 1', 'TV' => 'TV 1', 'SV' => 'SV 1', 'CS' => 'TS 1', 'TS' => 'TS 1', 'SS' => 'SS 1'],
                '13-16'  => ['CV' => 'CV 3', 'TV' => 'TV 2', 'SV' => 'SV 2', 'CS' => 'CS 3', 'TS' => 'TS 2', 'SS' => 'SS 3'],
                '17-23'  => ['CV' => 'CV 3', 'TV' => 'TV 3', 'SV' => 'SV 3', 'CS' => 'CS 3', 'TS' => 'TS 3', 'SS' => 'SS 3'],
                '24-39'  => ['CV' => 'CV 3', 'TV' => 'TV 4', 'SV' => 'SV 3', 'CS' => 'CS 3', 'TS' => 'TS 4', 'SS' => 'SS 3'],
                '40-49'  => ['CV' => 'CV 5', 'TV' => 'TV 5', 'SV' => 'SV 5', 'CS' => 'CS 5', 'TS' => 'TS 5', 'SS' => 'SS 5'],
                '50-100' => ['CV' => 'CV 5', 'TV' => 'TV 6', 'SV' => 'SV 5', 'CS' => 'CS 5', 'TS' => 'TS 6', 'SS' => 'SS 5'],
            ],
        ];

        if (false === $this->groups) {
            $this->groups = [];
            foreach ($yearRanges as $yearRange => $ageRanges) {
                if (false !== strpos($yearRange, '-')) {
                    $yearRange = explode('-', $yearRange);
                    for ($year = intval($yearRange[0]); $year <= intval($yearRange[1]); $year++) {
                        foreach ($ageRanges as $ageRange => $groupRanges) {
                            $ageRange = explode('-', $ageRange);
                            for ($age = intval($ageRange[0]); $age <= intval($ageRange[1]); $age++) {
                                $this->groups[$year][$age] = $groupRanges;
                            }
                        }
                    }
                }
            }
        }

        if (false !== strpos($item->bikeType, 'AK') || false !== strpos($item->gender, 'AK')) {
            return 'AK';
        }

        try {
            return $this->groups[(new Carbon($item->eventYear))->format('Y')][(new Carbon($item->eventYear))->diff((new Carbon($item->birthYear)))->format('%y')][$item->bikeType.$item->gender];
        } catch (\Exception $e) {
            return 'NaN';
        }
    }
}
