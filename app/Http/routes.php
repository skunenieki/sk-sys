<?php

$app->get('/10km/results/{eventYear}/groups', 'Skunenieki\System\Http\Controllers\IndividualResultsController@resultsByGroups');
$app->get('/10km/results/{eventYear}/summary', 'Skunenieki\System\Http\Controllers\IndividualResultsController@resultsSummary');

$app->get('/mtb/results/{eventYear}/groups', 'Skunenieki\System\Http\Controllers\MtbResultsController@resultsByGroups');

$app->group(['middleware' => 'auth'], function ($app) {
    $app->get('/', 'Skunenieki\System\Http\Controllers\MainViewController@render');

    $app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');

    $app->get('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');
    $app->post('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');
    $app->put('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');
    $app->patch('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');
    $app->options('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');
    $app->delete('adminer', 'Skunenieki\System\Http\Controllers\AdminerController@adminer');

    $app->get('test', 'Skunenieki\System\Http\Controllers\TestController@test');
    $app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

    $app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');
    $app->put('participants/{id}', 'Skunenieki\System\Http\Controllers\ParticipantController@update');

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
    $app->get('10km/diplomas/{eventYear}', 'Skunenieki\System\Http\Controllers\IndividualDiplomasController@diplomas');
    $app->get('10km/diplomas/{eventYear}/print', 'Skunenieki\System\Http\Controllers\IndividualDiplomasController@prepare');
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

    $app->get('mtb', 'Skunenieki\System\Http\Controllers\MtbController@index');
    $app->post('mtb', 'Skunenieki\System\Http\Controllers\MtbController@store');
    $app->get('mtb/finish', 'Skunenieki\System\Http\Controllers\MtbFinishController@finish');
    $app->get('mtb/finishnumbers', 'Skunenieki\System\Http\Controllers\MtbFinishNumberController@index');
    $app->post('mtb/finishnumbers', 'Skunenieki\System\Http\Controllers\MtbFinishNumberController@store');
    $app->delete('mtb/finishnumbers/{id}', 'Skunenieki\System\Http\Controllers\MtbFinishNumberController@destroy');
    $app->get('mtb/finishtimes', 'Skunenieki\System\Http\Controllers\MtbFinishTimeController@index');
    $app->post('mtb/finishtimes', 'Skunenieki\System\Http\Controllers\MtbFinishTimeController@store');
    $app->put('mtb/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\MtbFinishTimeController@update');
    $app->delete('mtb/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\MtbFinishTimeController@destroy');
    $app->get('mtb/{id}', 'Skunenieki\System\Http\Controllers\MtbController@show');
    $app->delete('mtb/{id}', 'Skunenieki\System\Http\Controllers\MtbController@destroy');
    $app->put('mtb/{id}', 'Skunenieki\System\Http\Controllers\MtbController@update');


    $app->get('triathlon/finish', 'Skunenieki\System\Http\Controllers\TriathlonFinishController@finish');
    $app->get('triathlon/finishnumbers', 'Skunenieki\System\Http\Controllers\TriathlonFinishNumberController@index');
    $app->post('triathlon/finishnumbers', 'Skunenieki\System\Http\Controllers\TriathlonFinishNumberController@store');
    $app->delete('triathlon/finishnumbers/{id}', 'Skunenieki\System\Http\Controllers\TriathlonFinishNumberController@destroy');
    $app->get('triathlon/finishtimes', 'Skunenieki\System\Http\Controllers\TriathlonFinishTimeController@index');
    $app->post('triathlon/finishtimes', 'Skunenieki\System\Http\Controllers\TriathlonFinishTimeController@store');
    $app->put('triathlon/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\TriathlonFinishTimeController@update');
    $app->delete('triathlon/finishtimes/{id}', 'Skunenieki\System\Http\Controllers\TriathlonFinishTimeController@destroy');

    $app->get('triathlon/teams', 'Skunenieki\System\Http\Controllers\TriathlonTeamsController@index');
    $app->post('triathlon/teams', 'Skunenieki\System\Http\Controllers\TriathlonTeamsController@store');
    $app->get('triathlon/teams/{id}', 'Skunenieki\System\Http\Controllers\TriathlonTeamsController@show');
    $app->put('triathlon/teams/{id}', 'Skunenieki\System\Http\Controllers\TriathlonTeamsController@update');
    $app->delete('triathlon/teams/{id}', 'Skunenieki\System\Http\Controllers\TriathlonTeamsController@destroy');

    $app->get('triathlon/individual', 'Skunenieki\System\Http\Controllers\TriathlonIndividualController@index');
    $app->post('triathlon/individual', 'Skunenieki\System\Http\Controllers\TriathlonIndividualController@store');
    $app->get('triathlon/individual/{id}', 'Skunenieki\System\Http\Controllers\TriathlonIndividualController@show');
    $app->put('triathlon/individual/{id}', 'Skunenieki\System\Http\Controllers\TriathlonIndividualController@update');
    $app->delete('triathlon/individual/{id}', 'Skunenieki\System\Http\Controllers\TriathlonIndividualController@destroy');

    $app->get('teams', 'Skunenieki\System\Http\Controllers\TeamController@index');
});


