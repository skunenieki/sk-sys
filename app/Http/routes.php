<?php

use Illuminate\Http\Response;

$app->get('/', function() {
    return view('index', [
        'x' => 'zzz',
    ]);
});

$app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

$app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

$app->get('mysqldump', function() {
    $url = parse_url(env('CLEARDB_DATABASE_URL'));
    $dump = new \MySQLDump(new \mysqli($url['host'], $url['user'], $url['pass'], substr($url['path'], 1)));

    return $dump->write();
});
