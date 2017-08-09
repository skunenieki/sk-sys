<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Mtb;
use Skunenieki\System\Models\Participant;
use Skunenieki\System\Models\IndividualStart;

class MtbController extends Controller
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

        $result = Mtb::where('id', '>', '0');

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

        return $result;
    }

    public function show($id)
    {
        $mtb = Mtb::find($id);

        return $mtb;
    }

    public function store(Request $request)
    {
        $ind = Mtb::where('number', $request->number)
                         ->where('eventYear', $request->eventYear)
                         ->first();

        if (null !== $ind) {
            return response(['error' => ['field' => 'number', 'msg' => 'Number already registered']], 400);
        }

        $ind = Mtb::where('name', $request->name)
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

        $ind = new Mtb;
        $ind->number        = $request->number;
        $ind->name          = $request->name;
        $ind->birthYear     = $request->birthYear;
        $ind->gender        = $request->gender;
        $ind->eventYear     = $request->eventYear;
        $ind->comment       = $request->comment;
        $ind->participantId = $participant->id;
        $ind->eventYear     = $request->input('eventYear', 2017);
        $ind->save();

        return $ind;
    }

    public function destroy($id)
    {
        Mtb::destroy($id);
        return;
    }

    public function update(Request $request, $id)
    {
        if ($request->input('lap1') === '') {
            $request->lap1 = null;
        }

        if ($request->input('lap2') === '') {
            $request->lap2 = null;
        }

        if ($request->input('lap3') === '') {
            $request->lap3 = null;
        }

        if ($request->input('finish') === '') {
            $request->finish = null;
        }

        if ($request->input('penalty') === '') {
            $request->penalty = null;
        }

        $mtb            = Mtb::find($id);
        $mtb->number    = $request->input('number');
        $mtb->name      = $request->input('name');
        $mtb->birthYear = $request->input('birthYear');
        $mtb->gender    = $request->input('gender');
        $mtb->lap1      = $request->lap1;
        $mtb->lap2      = $request->lap2;
        $mtb->lap3      = $request->lap3;
        $mtb->finish    = $request->finish;
        $mtb->penalty   = $request->penalty;
        $mtb->comment   = $request->input('comment');
        $mtb->save();

        return $mtb;
    }

    public function years()
    {
        return Mtb::select('eventYear')
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
