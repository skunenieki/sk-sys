<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Kids;
use Skunenieki\System\Models\Participant;

class KidsController extends Controller
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

        $result = Kids::where('id', '>', '0');

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
            $result->whereIn('start', explode(',', $start));
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

        return $result;
    }

    public function show($id)
    {
        $kids = Kids::find($id);

        return $kids;
    }

    public function store(Request $request)
    {
        $ind = Kids::where('number', $request->number)
                         ->where('eventYear', $request->eventYear)
                         ->first();

        if (null !== $ind) {
            return response(['error' => ['field' => 'number', 'msg' => 'Number already registered']], 400);
        }

        $ind = Kids::where('name', $request->name)
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

        $ind = new Kids;
        $ind->number        = $request->number;
        $ind->name          = $request->name;
        $ind->birthYear     = $request->birthYear;
        $ind->gender        = $request->gender;
        $ind->eventYear     = $request->eventYear;
        $ind->comment       = $request->comment;
        $ind->participantId = $participant->id;
        $ind->eventYear     = $request->input('eventYear', 2020); // @todo 2020
        $ind->save();

        return $ind;
    }

    public function destroy($id)
    {
        Kids::destroy($id);
        return;
    }

    public function update(Request $request, $id)
    {
        $kids            = Kids::find($id);
        $kids->number    = $request->input('number');
        $kids->name      = $request->input('name');
        $kids->birthYear = $request->input('birthYear');
        $kids->gender    = $request->input('gender');
        $kids->comment   = $request->input('comment');
        $kids->save();

        return $kids;
    }

    public function years()
    {
        return Kids::select('eventYear')
                   ->distinct()
                   ->orderBy('eventYear', 'desc')
                   ->get();
    }
}
