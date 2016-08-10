<?php


$date = new DateTime('00:34:00');

for ($i=1;$i<=400;$i++) {
    $sex = ($i%2-rand(1, 2)) === 0 ? 'V' : 'S';

    $by = rand(1930, 2014);

    $pid = 99999+$i;

    // echo "(NULL, $pid, {$i}, '{$i} {$i}', {$by}, '{$sex}', NULL, NULL, NULL, NULL, NULL, '', 2016, NULL, '2016-08-10 08:23:41', '2016-08-10 08:23:41'),\n";
    //

    $date->add(new DateInterval('PT20S'));
    $time = $date->format('h:i:s');


    echo "(NULL, {$i}, '{$time}', 0, 2016, '2016-08-10 08:35:11', '2016-08-10 08:35:11'),\n";
}


/*
INSERT INTO `individual_start` (`id`, `number`, `start`, `slot`, `eventYear`, `created_at`, `updated_at`)
VALUES
    (2593, 1, '00:34:40', 0, 2016, '2016-08-10 08:35:11', '2016-08-10 08:35:11');
*/
