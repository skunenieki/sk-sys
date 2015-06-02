<?php

$app->get('/', function() use ($app) {
    return view('index', ['name' => 'SK']);
});
