<?php

$app->group(['middleware' => 'auth'], function ($app) {
    $app->get('/', 'Skunenieki\System\Http\Controllers\MainViewController@render');

    $app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');
    $app->get('test', 'Skunenieki\System\Http\Controllers\TestController@test');
    $app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

    $app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

    $app->get('options', 'Skunenieki\System\Http\Controllers\OptionController@index');
    $app->get('options/{optionName}', 'Skunenieki\System\Http\Controllers\OptionController@show');
    $app->put('options/{optionName}', 'Skunenieki\System\Http\Controllers\OptionController@update');

    $app->get('events', 'Skunenieki\System\Http\Controllers\EventController@index');
    $app->post('events', 'Skunenieki\System\Http\Controllers\EventController@store');
    $app->get('events/{eventYear}', 'Skunenieki\System\Http\Controllers\EventController@show');
    $app->put('events/{eventYear}', 'Skunenieki\System\Http\Controllers\EventController@update');
    $app->delete('events/{eventYear}', 'Skunenieki\System\Http\Controllers\EventController@destroy');

    $app->get('10km', 'Skunenieki\System\Http\Controllers\IdividualController@index');
    $app->post('10km', 'Skunenieki\System\Http\Controllers\IdividualController@store');
    $app->get('10km/statistics', 'Skunenieki\System\Http\Controllers\IdividualController@statistics');
    $app->get('10km/years', 'Skunenieki\System\Http\Controllers\IdividualController@years');
    $app->get('10km/turn', 'Skunenieki\System\Http\Controllers\IdividualFinishController@turn');
    $app->get('10km/finish', 'Skunenieki\System\Http\Controllers\IdividualFinishController@finish');
    $app->get('10km/turns', 'Skunenieki\System\Http\Controllers\IdividualTurnController@index');
    $app->post('10km/turns', 'Skunenieki\System\Http\Controllers\IdividualTurnController@store');
    $app->delete('10km/turns/{id}', 'Skunenieki\System\Http\Controllers\IdividualTurnController@destroy');
    $app->get('10km/finishnumbers', 'Skunenieki\System\Http\Controllers\IdividualFinishNumberController@index');
    $app->post('10km/finishnumbers', 'Skunenieki\System\Http\Controllers\IdividualFinishNumberController@store');
    $app->delete('10km/finishnumbers/{id}', 'Skunenieki\System\Http\Controllers\IdividualFinishNumberController@destroy');
    $app->get('10km/finishtimes', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@index');
    $app->post('10km/finishtimes', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@store');
    $app->put('10km/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@update');
    $app->delete('10km/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\IdividualFinishTimeController@destroy');

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


