<?php

$app->get('/', function() {
    return view('index', [
        'x' => 'zzz',
    ]);
});

$app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');
$app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

// $app->get('validation/', 'Skunenieki\System\Http\Controllers\ValidationController@');

$app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

$app->get('10km', 'Skunenieki\System\Http\Controllers\IdividualController@index');
$app->get('10km/statistics', 'Skunenieki\System\Http\Controllers\IdividualController@statistics');
$app->get('10km/years', function() {
    $eventYears = Skunenieki\System\Models\Individual::select('eventYear')->distinct()->orderBy('eventYear', 'desc')->get();
    $result = [];
    foreach ($eventYears as $eventYear) {
        $result[] = [
            'eventYear' => $eventYear->eventYear->format('Y'),
        ];
    }

    return $result;
});
$app->get('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@show');
$app->delete('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@destroy');
$app->post('10km', 'Skunenieki\System\Http\Controllers\IdividualController@store');

$app->get('teams', 'Skunenieki\System\Http\Controllers\TeamController@index');
