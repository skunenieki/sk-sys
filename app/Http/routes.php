<?php

$app->get('/', function() {
    return view('index', [
        'x' => 'zzz',
    ]);
});

$app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');
$app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

$app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

