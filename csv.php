<?php

require __DIR__.'/vendor/autoload.php';

use Carbon\Carbon;
use League\Csv\Reader;

$csv = Reader::createFromPath(__DIR__.'/rezultati.csv');
$csv->setDelimiter(';');

$res = $csv->setOffset(3450)->fetchAll();

$items = [];
$i = 0;
foreach ($res as $item) {
    if ($item[0] !== null) {
        $items[] = [
            'number'    => (int) $item[0],
            'name'      => $item[1],
            'birthYear' => new Carbon((int) $item[2] . '-01-01'),
            'gender'    => $item[4],
            'bikeType'  => $item[5],
            'start'     => explode(' ', $item[7])[1],
            'turn'      => '0:00:00',
            'finish'    => explode(' ', $item[8])[1],
            'penalty'   => explode(' ', $item[9])[1] ?: '0:00:00',
            'comment'   => $item[11],
            'eventYear' => new Carbon('august '.(int) $item[12].' second saturday 8:00 AM'),
        ];
        var_dump($items[count($items)-1]);
    }
}

file_put_contents(__DIR__.'/data.txt', serialize($items));
