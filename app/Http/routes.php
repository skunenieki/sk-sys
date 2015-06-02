<?php

$app->get('/', function() {
    return view('index', [
        'x' => 'zzz',
    ]);
});

$app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');
