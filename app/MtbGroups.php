<?php

return [
    '2007-2009' => [
        '00-17'  => ['V' => 'V1', 'S' => 'S1'],
        '18-100' => ['V' => 'V2', 'S' => 'S2'],
    ],
    '2010-2010' => [
        '00-16'  => ['V' => 'V1', 'S' => 'S1'],
        '17-100' => ['V' => 'V2', 'S' => 'S2'],
    ],
    '2011-2013' => [
        '00-15'  => ['V' => 'V1', 'S' => 'S1'],
        '16-100' => ['V' => 'V2', 'S' => 'S2'],
    ],
    '2014-'.date('Y') => [
        '00-15'  => ['V' => 'V1', 'S' => 'S1'],
        '16-44'  => ['V' => 'V2', 'S' => 'S2'],
        '45-100' => ['V' => 'V3', 'S' => 'S3'],
    ],
];
