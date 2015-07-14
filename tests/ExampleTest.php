<?php

namespace Skunenieki\System\Tests;

use League\Csv\Reader;
use Skunenieki\System\Models\Individual;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndividualParticipantData()
    {
        $csv = Reader::createFromPath(__DIR__.'/../rezultati.csv');
        $csv->setDelimiter(';');

        $individuals = $csv->setOffset(1)->fetchAll();
        foreach ($individuals as $item) {
            if ($item[0] !== null) {
                $i = Individual::where('eventYear', (int) $item[12])
                               ->where('number', (int) $item[0])
                               ->first();

                $this->assertEquals($item[6], $i->group, "Group mismatch for {$item[0]} in {$item[12]} ({$item[1]})");
            }
        }
    }
}
