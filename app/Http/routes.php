<?php

$app->get('/', function() {
    return view('index', [
        'x' => 'zzz',
    ]);
});

$app->get('dbdump', 'Skunenieki\System\Http\Controllers\DBDumpController@dump');
$app->get('authenticate', 'Skunenieki\System\Http\Controllers\AuthenticationController@authenticate');

$app->get('participants', 'Skunenieki\System\Http\Controllers\ParticipantController@index');

$app->get('10km', 'Skunenieki\System\Http\Controllers\IdividualController@index');
$app->get('10km/statistics', 'Skunenieki\System\Http\Controllers\IdividualController@statistics');
$app->get('10km/years', 'Skunenieki\System\Http\Controllers\IdividualController@years');
$app->get('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@show');
$app->delete('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@destroy');
$app->put('10km/{id}', 'Skunenieki\System\Http\Controllers\IdividualController@update');
$app->post('10km', 'Skunenieki\System\Http\Controllers\IdividualController@store');

$app->get('teams', 'Skunenieki\System\Http\Controllers\TeamController@index');

use Knp\Snappy\Pdf;

$app->get('pdf', function() {
    $snappy = new Pdf(base_path().'/vendor/bin/wkhtmltopdf-amd64');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="file.pdf"');
    echo $snappy->getOutput('http://www.skunenieki.lv');
});

