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
            $result->whereIn('eventYear', explode(',', $eventYear));
        }

        if (false !== $birthYear) {
            $result->whereIn('birthYear', explode(',', $birthYear));
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
                '08-12' => ['PV' => 'PV 1'],
                '09-12' => ['PS' => 'PS 2'],
                '10-11' => ['SV' => 'SV 1'],
                '10-13' => ['KV' => 'KV 1', 'TV' => 'TV 1'],
                '12-12' => ['KS' => 'KS 2', 'TS' => 'TS 2'],
                '13-13' => ['CV' => 'CV 1'],
                '14-14' => ['PV' => 'PV 3'],
                '14-15' => ['CV' => 'CV 3'],
                '14-17' => ['CS' => 'CS 4'],
                '15-17' => ['SV' => 'SV 3', 'TV' => 'TV 3'],
                '17-17' => ['KS' => 'KS 4'],
                '18-22' => ['CV' => 'CV 5'],
                '18-23' => ['KV' => 'KV 5'],
                '19-28' => ['SV' => 'SV 5'],
                '26-26' => ['SS' => 'SS 6'],
                '31-31' => ['KS' => 'KS 8', 'KV' => 'KV 7'],
                '32-32' => ['CS' => 'CS 8'],
                '33-33' => ['TV' => 'TV 7'],
                '33-34' => ['SV' => 'SV 7'],
                '43-44' => ['SV' => 'SV 9'],
                '45-45' => ['CV' => 'CV 9'],
                '48-49' => ['CS' => 'CS 10'],
                '51-60' => ['CV' => 'CV 11'],
                '52-53' => ['SV' => 'SV 11'],
                '54-54' => ['CS' => 'CS 12'],
                '72-72' => ['CS' => 'CS 14'],
            ],
            '1998-1998' => [
                '05-11' => ['PS' => 'PS', 'PV' => 'PV'],
                '10-13' => ['TS' => 'TS 1'],
                '10-16' => ['TV' => 'TV 1'],
                '11-15' => ['CS' => 'CS 1'],
                '11-16' => ['SV' => 'SV 1'],
                '12-16' => ['CV' => 'CV 1'],
                '17-45' => ['SV' => 'SV 2'],
                '18-19' => ['CS' => 'CS 2'],
                '18-31' => ['CV' => 'CV 2'],
                '18-34' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '31-31' => ['CS' => 'CS 3'],
                '44-44' => ['CV' => 'CV 3'],
                '46-52' => ['CV' => 'CV 4'],
                '47-73' => ['CS' => 'CS 4'],
                '52-62' => ['SV' => 'SV 3'],
                '54-54' => ['SS' => 'SS'],
            ],
            '1999-1999' => [
                '05-12' => ['PS' => 'BS', 'TV' => 'BV', 'PV' => 'BV', 'KS' => 'BS', 'KV' => 'BV', 'TS' => 'BS', 'CS' => 'BS', 'CV' => 'BV'],
                '06-13' => ['BS' => 'BS'],
                '13-13' => ['PS' => 'PS', 'TS' => 'BS'],
                '13-16' => ['TV' => 'TV 1'],
                '14-16' => ['CS' => 'CS 1', 'CV' => 'CV 1', 'TS' => 'TS 1'],
                '15-15' => ['SV' => 'SV 1'],
                '17-29' => ['CV' => 'CV 2'],
                '17-43' => ['TV' => 'TV 2'],
                '17-45' => ['SV' => 'SV 2'],
                '19-22' => ['CS' => 'CS 2'],
                '19-55' => ['SS' => 'SS'],
                '25-37' => ['TS' => 'TS 2'],
                '31-31' => ['CS' => 'CS 3'],
                '42-45' => ['CV' => 'CV 3'],
                '46-53' => ['CV' => 'CV 4'],
                '46-61' => ['CS' => 'CS 4'],
                '52-72' => ['SV' => 'SV 3'],
            ],
            '2000-2000' => [
                '04-11' => ['BS' => 'BS', 'PV' => 'BV', 'PS' => 'BS'],
                '05-11' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '11-13' => ['PV' => 'PV'],
                '11-15' => ['CS' => 'CS 1', 'TS' => 'TS 1'],
                '11-17' => ['TV' => 'TV 1'],
                '11-56' => ['SS' => 'SS'],
                '12-13' => ['PS' => 'PS'],
                '13-15' => ['SV' => 'SV 1'],
                '15-17' => ['CV' => 'CV 1'],
                '17-29' => ['CS' => 'CS 2'],
                '17-44' => ['SV' => 'SV 2'],
                '18-30' => ['CV' => 'CV 2'],
                '18-39' => ['TS' => 'TS 2'],
                '18-44' => ['TV' => 'TV 2'],
                '36-43' => ['CV' => 'CV 3'],
                '37-37' => ['CS' => 'CS 3'],
                '46-58' => ['SV' => 'SV 3'],
                '49-62' => ['CV' => 'CV 4'],
                '51-51' => ['TS' => 'TS 3'],
                '52-59' => ['CS' => 'CS 4'],
            ],
            '2001-2001' => [
                '05-08' => ['CV' => 'BV', 'CS' => 'BS'],
                '09-11' => ['CV' => 'CV 1', 'CS' => 'CS 1', 'TV' => 'TV 1', 'TS' => 'CS 1'],
                '12-16' => ['CV' => 'CV 2', 'TS' => 'TS 2', 'TV' => 'TV 2'],
                '13-14' => ['CS' => 'CS 2'],
                '14-16' => ['SV' => 'SV 2'],
                '17-39' => ['TS' => 'TS 3'],
                '17-43' => ['TV' => 'TV 3'],
                '17-45' => ['SV' => 'SV 3'],
                '18-28' => ['CV' => 'CV 3'],
                '21-25' => ['CS' => 'CS 3'],
                '26-30' => ['SS' => 'SS 3'],
                '40-44' => ['CV' => 'CV 4', 'CS' => 'CS 4'],
                '47-60' => ['SV' => 'SV 5', 'TS' => 'TS 5', 'CV' => 'CV 5', 'SS' => 'SS 5', 'TV' => 'TV 5'],
            ],
            '2002-2002' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-11' => ['CV' => 'CV 1', 'TV' => 'TV 1', 'CS' => 'CS 1', 'TS' => 'TS 1'],
                '11-16' => ['TV' => 'TV 2', 'SV' => 'SV 2', 'CS' => 'CS 2', 'TS' => 'TS 2', 'CV' => 'CV 2'],
                '17-45' => ['SS' => 'SS 3', 'CV' => 'CV 3', 'SV' => 'SV 3', 'TV' => 'TV 3', 'TS' => 'TS 3', 'CS' => 'CS 3'],
                '31-43' => ['CS' => 'CS 4'],
                '32-45' => ['CV' => 'CV 4'],
                '46-68' => ['SV' => 'SV 5', 'TV' => 'TV 5'],
                '50-54' => ['CV' => 'CV 5', 'TS' => 'TS 5', 'SS' => 'SS 5'],
            ],
            '2003-2003' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-11' => ['TS' => 'TS 1', 'TV' => 'TV 1'],
                '09-12' => ['CV' => 'CV 1'],
                '12-16' => ['SV' => 'SV 2', 'TS' => 'TS 2', 'TV' => 'TV 2'],
                '13-28' => ['SS' => 'SS 3'],
                '14-14' => ['CS' => 'CS 2', 'CV' => 'CV 2'],
                '15-45' => ['TV' => 'TV 3'],
                '17-42' => ['SV' => 'SV 3'],
                '17-43' => ['TS' => 'TS 3'],
                '24-26' => ['CS' => 'CS 3'],
                '28-30' => ['CV' => 'CV 3'],
                '32-47' => ['CS' => 'CS 4'],
                '33-39' => ['CV' => 'CV 4'],
                '46-48' => ['TV' => 'TV 5'],
                '46-55' => ['CV' => 'CV 5'],
                '47-64' => ['SV' => 'SV 5'],
                '60-62' => ['TS' => 'TS 5'],
            ],
            '2004-2004' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-10' => ['TS' => 'TS 1'],
                '09-11' => ['TV' => 'TV 1'],
                '10-11' => ['CV' => 'CV 1'],
                '12-15' => ['SV' => 'SV 2'],
                '12-16' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '13-14' => ['CS' => 'CS 2'],
                '13-29' => ['SS' => 'SS 3'],
                '15-15' => ['CV' => 'CV 2'],
                '17-21' => ['CV' => 'CV 3'],
                '17-40' => ['TV' => 'TV 3'],
                '17-43' => ['TS' => 'TS 3'],
                '22-28' => ['SV' => 'SV 3'],
                '24-24' => ['CS' => 'CS 3'],
                '31-34' => ['CV' => 'CV 4'],
                '33-48' => ['CS' => 'CS 4'],
                '45-45' => ['SS' => 'SS 5'],
                '46-49' => ['TV' => 'TV 5'],
                '47-63' => ['TS' => 'TS 5'],
                '48-65' => ['SV' => 'SV 5'],
                '52-56' => ['CV' => 'CV 5'],
            ],
            '2005-2005' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1' ,'CS' => 'TS 1', 'SV' => 'SV 1', 'CV' => 'TV 1'],
                '13-16' => ['TV' => 'TV 2'],
                '14-30' => ['SS' => 'SS 3'],
                '14-35' => ['CV' => 'CV 3'],
                '15-16' => ['TS' => 'TS 2'],
                '16-16' => ['SV' => 'SV 2'],
                '17-18' => ['TS' => 'TS 3'],
                '17-21' => ['TV' => 'TV 3'],
                '19-29' => ['SV' => 'SV 3'],
                '24-39' => ['TV' => 'TV 4'],
                '25-39' => ['TS' => 'TS 4'],
                '26-34' => ['CS' => 'CS 3'],
                '40-53' => ['TS' => 'TS 5'],
                '40-54' => ['TV' => 'TV 5'],
                '40-64' => ['CS' => 'CS 5'],
                '41-54' => ['CV' => 'CV 5'],
                '47-60' => ['SV' => 'SV 5'],
                '51-51' => ['SS' => 'SS 5'],
            ],
            '2006-2006' => [
                '05-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1'],
                '12-12' => ['SV' => 'SV 1'],
                '13-15' => ['SV' => 'SV 2'],
                '13-16' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '13-36' => ['CV' => 'CV 3'],
                '17-23' => ['TS' => 'TS 3', 'TV' => 'TV 3'],
                '17-39' => ['SV' => 'SV 3'],
                '24-38' => ['TV' => 'TV 4'],
                '25-31' => ['SS' => 'SS 3'],
                '25-39' => ['TS' => 'TS 4'],
                '26-35' => ['CS' => 'CS 3'],
                '40-58' => ['CV' => 'CV 5'],
                '40-60' => ['TS' => 'TS 5'],
                '40-64' => ['TV' => 'TV 5'],
                '41-70' => ['SV' => 'SV 5'],
                '50-65' => ['CS' => 'CS 5'],
                '52-63' => ['SS' => 'SS 5'],
            ],
            '2007-2007' => [
                '05-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-11' => ['TS' => 'TS 1', 'CS' => 'TS 1'],
                '09-12' => ['TV' => 'TV 1', 'CV' => 'TV 1'],
                '12-12' => ['SV' => 'SV 1'],
                '13-16' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '14-16' => ['SV' => 'SV 2'],
                '15-37' => ['CV' => 'CV 3'],
                '17-23' => ['TS' => 'TS 3', 'TV' => 'TV 3'],
                '17-39' => ['SV' => 'SV 3'],
                '20-32' => ['SS' => 'SS 3'],
                '24-38' => ['TV' => 'TV 4'],
                '24-39' => ['TS' => 'TS 4'],
                '29-36' => ['CS' => 'CS 3'],
                '40-57' => ['TV' => 'TV 5'],
                '41-55' => ['TS' => 'TS 5'],
                '42-66' => ['CS' => 'CS 5'],
                '43-62' => ['SV' => 'SV 5'],
                '49-59' => ['CV' => 'CV 5'],
            ],
            '2008-2008' => [
                '04-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1', 'CV' => 'TV 1'],
                '13-16' => ['TV' => 'TV 2'],
                '13-35' => ['CV' => 'CV 3'],
                '14-15' => ['TS' => 'TS 2'],
                '15-15' => ['SV' => 'SV 2'],
                '15-33' => ['SS' => 'SS 3'],
                '17-22' => ['TV' => 'TV 3'],
                '17-23' => ['TS' => 'TS 3'],
                '18-32' => ['SV' => 'SV 3'],
                '24-39' => ['TV' => 'TV 4'],
                '26-38' => ['TS' => 'TS 4'],
                '33-37' => ['CS' => 'CS 3'],
                '40-67' => ['TS' => 'TS 5'],
                '41-65' => ['SV' => 'SV 5'],
                '44-56' => ['CV' => 'CV 5'],
                '44-63' => ['TV' => 'TV 5'],
            ],
            '2009-2009' => [
                '05-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1', 'CV' => 'TV 1'],
                '13-15' => ['TS' => 'TS 2'],
                '13-16' => ['TV' => 'TV 2'],
                '13-38' => ['CS' => 'CS 3'],
                '14-14' => ['SV' => 'SV 2'],
                '15-39' => ['CV' => 'CV 3'],
                '17-23' => ['TS' => 'TS 3', 'TV' => 'TV 3'],
                '17-35' => ['SV' => 'SV 3'],
                '22-22' => ['SS' => 'SS 3'],
                '24-38' => ['TV' => 'TV 4'],
                '25-39' => ['TS' => 'TS 4'],
                '40-62' => ['SV' => 'SV 5'],
                '41-57' => ['TS' => 'TS 5'],
                '41-64' => ['TV' => 'TV 5'],
                '44-68' => ['CS' => 'CS 5'],
                '44-70' => ['CV' => 'CV 5'],
            ],
            '2010-2010' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1', 'CV' => 'TV 1'],
                '13-14' => ['TS' => 'TS 2'],
                '13-16' => ['TV' => 'TV 2'],
                '14-39' => ['CS' => 'CS 3'],
                '15-15' => ['SV' => 'SV 2'],
                '16-35' => ['SS' => 'SS 3'],
                '17-23' => ['TS' => 'TS 3', 'TV' => 'TV 3'],
                '20-36' => ['SV' => 'SV 3'],
                '23-38' => ['CV' => 'CV 3'],
                '24-39' => ['TS' => 'TS 4', 'TV' => 'TV 4'],
                '40-40' => ['SS' => 'SS 5'],
                '40-59' => ['CV' => 'CV 5'],
                '40-69' => ['TS' => 'TS 5'],
                '41-57' => ['TV' => 'TV 5'],
                '41-65' => ['SV' => 'SV 5'],
                '42-67' => ['CS' => 'CS 5'],
            ],
            '2011-2011' => [
                '06-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1', 'CS' => 'TS 1', 'CV' => 'TV 1'],
                '13-16' => ['SV' => 'SV 2', 'TS' => 'TS 2', 'TV' => 'TV 2'],
                '16-38' => ['CV' => 'CV 3'],
                '17-23' => ['TV' => 'TV 3'],
                '17-36' => ['SS' => 'SS 3'],
                '18-23' => ['TS' => 'TS 3'],
                '21-35' => ['SV' => 'SV 3'],
                '24-38' => ['TS' => 'TS 4'],
                '24-39' => ['TV' => 'TV 4'],
                '33-39' => ['CS' => 'CS 3'],
                '40-66' => ['TV' => 'TV 5'],
                '40-70' => ['CS' => 'CS 5', 'TS' => 'TS 5'],
                '41-41' => ['SS' => 'SS 5'],
                '41-66' => ['CV' => 'CV 5'],
                '47-47' => ['SV' => 'SV 5'],
            ],
            '2012-2012' => [
                '05-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1'],
                '11-11' => ['SV' => 'SV 1'],
                '13-16' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '14-15' => ['SV' => 'SV 2'],
                '14-37' => ['SS' => 'SS 3'],
                '17-23' => ['TV' => 'TV 3'],
                '17-39' => ['CV' => 'CV 3'],
                '18-36' => ['SV' => 'SV 3'],
                '19-23' => ['TS' => 'TS 3'],
                '21-35' => ['CS' => 'CS 3'],
                '24-39' => ['TS' => 'TS 4', 'TV' => 'TV 4'],
                '40-48' => ['TV' => 'TV 5'],
                '40-49' => ['TS' => 'TS 5'],
                '42-44' => ['CV' => 'CV 5'],
                '42-71' => ['CS' => 'CS 5'],
                '47-65' => ['SV' => 'SV 5'],
                '50-64' => ['TS' => 'TS 6'],
                '51-69' => ['TV' => 'TV 6'],
            ],
            '2013-2013' => [
                '04-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['SS' => 'SS 1', 'TS' => 'TS 1', 'TV' => 'TV 1', 'CS' => 'TS 1'],
                '12-12' => ['SV' => 'SV 1'],
                '13-16' => ['TS' => 'TS 2', 'TV' => 'TV 2'],
                '14-33' => ['CV' => 'CV 3'],
                '15-36' => ['CS' => 'CS 3'],
                '15-38' => ['SS' => 'SS 3'],
                '17-23' => ['TV' => 'TV 3'],
                '18-23' => ['TS' => 'TS 3'],
                '20-37' => ['SV' => 'SV 3'],
                '24-38' => ['TS' => 'TS 4'],
                '24-39' => ['TV' => 'TV 4'],
                '40-49' => ['TS' => 'TS 5'],
                '40-53' => ['CV' => 'CV 5'],
                '40-72' => ['CS' => 'CS 5'],
                '41-49' => ['TV' => 'TV 5'],
                '44-49' => ['SV' => 'SV 5'],
                '50-60' => ['TS' => 'TS 6'],
                '50-69' => ['TV' => 'TV 6'],
            ],
            '2014-2014' => [
                '00-08' => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12' => ['TS' => 'TS 1', 'TV' => 'TV 1', 'SV' => 'SV 1', 'SS' => 'SS 1'],
                '13-16' => ['TV' => 'TV 2', 'TS' => 'TS 2', 'SV' => 'SV 2'],
                '13-39' => ['SS' => 'SS 3'],
                '15-27' => ['CV' => 'CV 3'],
                '18-21' => ['TS' => 'TS 3'],
                '18-23' => ['TV' => 'TV 3'],
                '24-24' => ['CS' => 'CS 3'],
                '24-38' => ['SV' => 'SV 3'],
                '24-39' => ['TS' => 'TS 4', 'TV' => 'TV 4'],
                '40-49' => ['TS' => 'TS 5', 'TV' => 'TV 5'],
                '41-44' => ['CV' => 'CV 5'],
                '42-58' => ['SV' => 'SV 5'],
                '43-43' => ['SS' => 'SS 5'],
                '43-58' => ['CS' => 'CS 5'],
                '50-73' => ['TV' => 'TV 6', 'TS' => 'TS 6'],
            ],
            '2015-'.date('Y') => [
                '00-08'  => ['CV' => 'BV', 'TV' => 'BV', 'SV' => 'BV', 'CS' => 'BS', 'TS' => 'BS', 'SS' => 'BS'],
                '09-12'  => ['CV' => 'TV 1', 'TV' => 'TV 1', 'SV' => 'SV 1', 'CS' => 'TS 1', 'TS' => 'TS 1', 'SS' => 'SS 1'],
                '13-16'  => ['CV' => 'CV', 'TV' => 'TV 2', 'SV' => 'SV 2', 'CS' => 'CS', 'TS' => 'TS 2', 'SS' => 'SS 3'],
                '17-23'  => ['CV' => 'CV', 'TV' => 'TV 3', 'SV' => 'SV 3', 'CS' => 'CS', 'TS' => 'TS 3', 'SS' => 'SS 3'],
                '24-39'  => ['CV' => 'CV', 'TV' => 'TV 4', 'SV' => 'SV 4', 'CS' => 'CS', 'TS' => 'TS 4', 'SS' => 'SS 3'],
                '40-49'  => ['CV' => 'CV', 'TV' => 'TV 5', 'SV' => 'SV 5', 'CS' => 'CS', 'TS' => 'TS 5', 'SS' => 'SS 5'],
                '50-100' => ['CV' => 'TV 6', 'TV' => 'TV 6', 'SV' => 'SV 6', 'CS' => 'TS 6', 'TS' => 'TS 6', 'SS' => 'SS 5'],
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
                                if (false === isset($this->groups[$year][$age])) {
                                     $this->groups[$year][$age] = [];
                                }
                                $this->groups[$year][$age] = array_merge($this->groups[$year][$age], $groupRanges);
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
            // dd($item->eventYear-$item->birthYear);
            // dd($item->eventYear);
            // dd($item->bikeType.$item->gender);
            return $this->groups[$item->eventYear][$item->eventYear-$item->birthYear][strtoupper($item->bikeType.$item->gender)];
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // dd();
            return 'NaN';
        }
    }
}
