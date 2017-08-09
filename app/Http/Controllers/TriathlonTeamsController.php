<?php

namespace Skunenieki\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Skunenieki\System\Models\Participant;
use Skunenieki\System\Models\TriathlonTeam;

class TriathlonTeamsController extends Controller
{
    public function index(Request $request)
    {
        $eventYear = $request->get('eventYear', false);
        $number = $request->get('number', false);

        $result = TriathlonTeam::where('id', '>', '0');

        if (false !== $number) {
            $result->whereIn('number', explode(',', $number));
        }

        if (false !== $eventYear) {
            $result->whereIn('eventYear', explode(',', $eventYear));
        }

        return $result->get();
    }

    public function show($id)
    {
        return TriathlonTeam::find($id);
    }

    public function update(Request $request, $id)
    {
        $swimmer = Participant::where('name', $request->input('swimmerName'))
                              ->where('birthYear', $request->input('swimmerBirthYear'))
                              ->first();

        if (null === $swimmer) {
            $swimmer = new Participant;
            $swimmer->name      = $request->input('swimmerName');
            $swimmer->gender    = $request->input('swimmerGender');
            $swimmer->birthYear = $request->input('swimmerBirthYear');
            $swimmer->save();
        }

        $biker = Participant::where('name', $request->input('bikerName'))
                            ->where('birthYear', $request->input('bikerBirthYear'))
                            ->first();

        if (null === $biker) {
            $biker = new Participant;
            $biker->name      = $request->input('bikerName');
            $biker->gender    = $request->input('bikerGender');
            $biker->birthYear = $request->input('bikerBirthYear');
            $biker->save();
        }

        $runner = Participant::where('name', $request->input('runnerName'))
                             ->where('birthYear', $request->input('runnerBirthYear'))
                             ->first();


        if (null === $runner) {
            $runner = new Participant;
            $runner->name      = $request->input('runnerName');
            $runner->gender    = $request->input('runnerGender');
            $runner->birthYear = $request->input('runnerBirthYear');
            $runner->save();
        }


        if ($request->finish === '') {
            $request->finish = null;
        }

        $team            = TriathlonTeam::find($id);
        $team->number    = $request->input('number');
        $team->name      = $request->input('name');

        $team->swimmerParticipantId = $swimmer->id;
        $team->swimmerName          = $swimmer->name;
        $team->swimmerBirthYear     = $swimmer->birthYear;
        $team->swimmerGender        = $swimmer->gender;

        $team->bikerParticipantId = $biker->id;
        $team->bikerName          = $biker->name;
        $team->bikerBirthYear     = $biker->birthYear;
        $team->bikerGender        = $biker->gender;

        $team->runnerParticipantId = $runner->id;
        $team->runnerName          = $runner->name;
        $team->runnerBirthYear     = $runner->birthYear;
        $team->runnerGender        = $runner->gender;

        if ($request->input('finish') === '') {
            $request->finish = null;
        }

        if ($request->input('penalty') === '') {
            $request->penalty = null;
        }

        $team->finish    = $request->finish;
        $team->penalty   = $request->penalty;
        $team->eventYear = $request->input('eventYear', 2017);
        $team->comment   = $request->input('comment');
        $team->save();

        return $team;
    }

    public function store(Request $request)
    {
        $swimmerInput = $request->input('swimmer', null);
        $bikerInput   = $request->input('biker', null);
        $runnerInput  = $request->input('runner', null);

        $swimmer = Participant::where('name', $swimmerInput['name'])
                              ->where('birthYear', $swimmerInput['birthYear'])
                              ->first();

        if (null === $swimmer) {
            $swimmer = new Participant;
            $swimmer->name      = $swimmerInput['name'];
            $swimmer->gender    = $swimmerInput['gender'];
            $swimmer->birthYear = $swimmerInput['birthYear'];
            $swimmer->save();
        }

        $biker = Participant::where('name', $bikerInput['name'])
                            ->where('birthYear', $bikerInput['birthYear'])
                            ->first();

        if (null === $biker) {
            $biker = new Participant;
            $biker->name      = $bikerInput['name'];
            $biker->gender    = $bikerInput['gender'];
            $biker->birthYear = $bikerInput['birthYear'];
            $biker->save();
        }

        $runner = Participant::where('name', $runnerInput['name'])
                             ->where('birthYear', $runnerInput['birthYear'])
                             ->first();


        if (null === $runner) {
            $runner = new Participant;
            $runner->name      = $runnerInput['name'];
            $runner->gender    = $runnerInput['gender'];
            $runner->birthYear = $runnerInput['birthYear'];
            $runner->save();
        }

        $team            = new TriathlonTeam;
        $team->number    = $request->input('number');
        $team->name      = $request->input('teamName');
        $team->eventYear = $request->input('eventYear', 2017);
        $team->comment   = $request->input('comment');

        $team->swimmerParticipantId = $swimmer->id;
        $team->swimmerName          = $swimmer->name;
        $team->swimmerBirthYear     = $swimmer->birthYear;
        $team->swimmerGender        = $swimmer->gender;

        $team->bikerParticipantId = $biker->id;
        $team->bikerName          = $biker->name;
        $team->bikerBirthYear     = $biker->birthYear;
        $team->bikerGender        = $biker->gender;

        $team->runnerParticipantId = $runner->id;
        $team->runnerName          = $runner->name;
        $team->runnerBirthYear     = $runner->birthYear;
        $team->runnerGender        = $runner->gender;

        $team->save();

        return $team;
    }

    public function destroy($id)
    {
        TriathlonTeam::destroy($id);
        return;
    }
}
