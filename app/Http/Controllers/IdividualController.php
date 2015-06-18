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
            $result->whereIn('number', explode(',', $number));
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
            if (null === $item->group) {
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
        $ind->bikeType      = null;
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
            '1997-1997' => [
                '8-12' => ['PV' => 'PV 1'],
                '9-12' => ['PS' => 'PS 2'],
                '48-49' => ['CS' => 'CS 10'],
                '54-54' => ['CS' => 'CS 12'],
                '72-72' => ['CS' => 'CS 14'],
                '14-17' => ['CS' => 'CS 4'],
                '32-32' => ['CS' => 'CS 8'],
                '13-13' => ['CV' => 'CV 1'],
                '51-60' => ['CV' => 'CV 11'],
                '14-15' => ['CV' => 'CV 3'],
                '18-22' => ['CV' => 'CV 5'],
                '45-45' => ['CV' => 'CV 9'],
                '12-12' => ['KS' => 'KS 2'],
                '17-17' => ['KS' => 'KS 4'],
                '31-31' => ['KS' => 'KS 8'],
                '10-13' => ['KV' => 'KV 1'],
                '18-23' => ['KV' => 'KV 5'],
                '31-31' => ['KV' => 'KV 7'],
                '14-14' => ['PV' => 'PV 3'],
                '26-26' => ['SS' => 'SS 6'],
                '10-11' => ['SV' => 'SV 1'],
                '52-53' => ['SV' => 'SV 11'],
                '15-17' => ['SV' => 'SV 3'],
                '19-28' => ['SV' => 'SV 5'],
                '33-34' => ['SV' => 'SV 7'],
                '43-44' => ['SV' => 'SV 9'],
                '12-12' => ['TS' => 'TS 2'],
                '10-13' => ['TV' => 'TV 1'],
                '15-17' => ['TV' => 'TV 3'],
                '33-33' => ['TV' => 'TV 7'],
            ],
            '1998-1998' => [
                '5-11' => ['PS' => 'PS'],
                '5-11' => ['PV' => 'PV'],
                '11-15' => ['CS' => 'CS 1'],
                '18-19' => ['CS' => 'CS 2'],
                '31-31' => ['CS' => 'CS 3'],
                '47-73' => ['CS' => 'CS 4'],
                '12-16' => ['CV' => 'CV 1'],
                '18-31' => ['CV' => 'CV 2'],
                '44-44' => ['CV' => 'CV 3'],
                '46-52' => ['CV' => 'CV 4'],
                '11-16' => ['SV' => 'SV 1'],
                '17-45' => ['SV' => 'SV 2'],
                '52-62' => ['SV' => 'SV 3'],
                '10-13' => ['TS' => 'TS 1'],
                '18-34' => ['TS' => 'TS 2'],
                '10-16' => ['TV' => 'TV 1'],
                '18-34' => ['TV' => 'TV 2'],
                '54-54' => ['SS' => 'SS'],
            ],
            '1999-1999' => [
                '6-13' => ['BS' => 'BS'],
                '5-12' => ['BV' => 'BV'],
                '13-13' => ['PS' => 'PS'],
                '19-55' => ['SS' => 'SS'],
                '14-16' => ['CS' => 'CS 1'],
                '19-22' => ['CS' => 'CS 2'],
                '31-31' => ['CS' => 'CS 3'],
                '46-61' => ['CS' => 'CS 4'],
                '14-16' => ['CV' => 'CV 1'],
                '17-29' => ['CV' => 'CV 2'],
                '42-45' => ['CV' => 'CV 3'],
                '46-53' => ['CV' => 'CV 4'],
                '15-15' => ['SV' => 'SV 1'],
                '17-45' => ['SV' => 'SV 2'],
                '52-72' => ['SV' => 'SV 3'],
                '14-16' => ['TS' => 'TS 1'],
                '25-37' => ['TS' => 'TS 2'],
                '13-16' => ['TV' => 'TV 1'],
                '17-43' => ['TV' => 'TV 2'],
            ],
            '2000-2000' => [
                '4-11' => ['BS' => 'BS'],
                '5-11' => ['BV' => 'BV'],
                '11-15' => ['CS' => 'CS 1'],
                '17-29' => ['CS' => 'CS 2'],
                '37-37' => ['CS' => 'CS 3'],
                '52-59' => ['CS' => 'CS 4'],
                '15-17' => ['CV' => 'CV 1'],
                '18-30' => ['CV' => 'CV 2'],
                '36-43' => ['CV' => 'CV 3'],
                '49-62' => ['CV' => 'CV 4'],
                '12-13' => ['PS' => 'PS'],
                '11-13' => ['PV' => 'PV'],
                '11-56' => ['SS' => 'SS'],
                '13-15' => ['SV' => 'SV 1'],
                '17-44' => ['SV' => 'SV 2'],
                '46-58' => ['SV' => 'SV 3'],
                '11-15' => ['TS' => 'TS 1'],
                '18-39' => ['TS' => 'TS 2'],
                '51-51' => ['TS' => 'TS 3'],
                '11-17' => ['TV' => 'TV 1'],
                '18-44' => ['TV' => 'TV 2'],
            ],
            '2001-2001' => [
                '5-8' => ['BS' => 'BS'],
                '5-8' => ['BV' => 'BV'],
                '9-9' => ['CV' => 'CV 1'],
                '9-11' => ['CS' => 'CS 1', 'TV' => 'TV 1'],
                '13-14' => ['CS' => 'CS 2'],
                '21-25' => ['CS' => 'CS 3'],
                '40-40' => ['CS' => 'CS 4'],
                '12-16' => ['CV' => 'CV 2'],
                '18-28' => ['CV' => 'CV 3'],
                '31-44' => ['CV' => 'CV 4'],
                '48-53' => ['CV' => 'CV 5'],
                '26-30' => ['SS' => 'SS 3'],
                '48-57' => ['SS' => 'SS 5'],
                '14-16' => ['SV' => 'SV 2'],
                '17-45' => ['SV' => 'SV 3'],
                '52-56' => ['SV' => 'SV 5'],
                '12-16' => ['TS' => 'TS 2'],
                '17-39' => ['TS' => 'TS 3'],
                '52-60' => ['TS' => 'TS 5'],
                '12-16' => ['TV' => 'TV 2'],
                '17-43' => ['TV' => 'TV 3'],
                '47-47' => ['TV' => 'TV 5'],
            ],
            '2001-2015' => [
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
