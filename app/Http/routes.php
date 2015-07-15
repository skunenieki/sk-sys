<?php

$app->group(['middleware' => 'auth'], function ($app) {
    $app->get('/', function() {
        return view('index', [
            'x' => 'zzz',
        ]);
    });

    $app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');
    $app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

    $app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

    $app->get('10km', 'Skunenieki\System\Http\Controllers\IdividualController@index');
    $app->post('10km', 'Skunenieki\System\Http\Controllers\IdividualController@store');
    $app->get('10km/statistics', 'Skunenieki\System\Http\Controllers\IdividualController@statistics');
    $app->get('10km/years', 'Skunenieki\System\Http\Controllers\IdividualController@years');
    $app->get('10km/turn', 'Skunenieki\System\Http\Controllers\IdividualFinishController@finish');
    $app->get('10km/turns', 'Skunenieki\System\Http\Controllers\IdividualTurnController@index');
    $app->post('10km/turns', 'Skunenieki\System\Http\Controllers\IdividualTurnController@store');
    $app->get('10km/finishtimes', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@index');
    $app->post('10km/finishtimes', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@store');
    $app->put('10km/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@update');



    $app->get('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@show');
    $app->delete('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@destroy');
    $app->put('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@update');

    $app->get('teams', 'Skunenieki\System\Http\Controllers\TeamController@index');
});

$app->get('results/{eventYear}/groups', 'Skunenieki\System\Http\Controllers\ResultsController@resultsByGroups');
$app->get('results/{eventYear}/summary', 'Skunenieki\System\Http\Controllers\ResultsController@resultsSummary');

$app->get('print', function() {
    return view('print');
});


