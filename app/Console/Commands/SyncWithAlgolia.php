<?php

namespace Skunenieki\System\Console\Commands;

use Exception;
use AlgoliaSearch\Client;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Individual;

class SyncWithAlgolia extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'algolia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchoronize with Algolia service';

    public function __construct()
    {
        parent::__construct();

        $this->algolia = new Client(env('ALGOLIA_APP_ID'), env('ALGOLIA_SECRET'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $index = $this->algolia->initIndex('skunenieki');

        while (true) {
            $index->clearIndex();

            $data = Individual::where('eventYear', 2015)
                              ->whereNotNull('finish');

            $results = [];
            $results2 = [];
            foreach ($data->get() as $individual) {
                $results[$individual->gender][$individual->group][$individual->resultInSeconds][$individual->id] = $individual;
            }

            $this->ksortRecursive($results);

            foreach ($results as $genders) {
                foreach ($genders as $groups) {
                    $i = 1;
                    foreach ($groups as $resultsInSec) {
                        $last = end($resultsInSec);
                        foreach ($resultsInSec as $individual) {
                            $results2[$individual->gender][$individual->resultInSeconds][$individual->id] = [
                                'objectID'      => $individual->id,
                                'name'          => $individual->name,
                                'birthYear'     => $individual->birthYear,
                                'result'        => $individual->result,
                                'group'         => $individual->group,
                                'rankInGroup'   => $i,
                            ];

                            if (count($resultsInSec) > 1 && $individual === $last) {
                                $i += count($resultsInSec);
                            }

                            if (count($resultsInSec) < 2) {
                                $i++;
                            }
                        }
                    }
                }
            }

            $this->ksortRecursive($results2);

            $this->output->progressStart($data->count());

            foreach ($results2 as $groups) {
                $i = 1;
                foreach ($groups as $resultsInSec) {
                    $last = end($resultsInSec);
                    foreach ($resultsInSec as $individual) {
                        $last['rankInSummary']       = $i;
                        $individual['rankInSummary'] = $i;
                        $index->saveObject($individual);

                        if (count($resultsInSec) > 1 && $individual === $last) {
                            $i += count($resultsInSec);
                        }

                        if (count($resultsInSec) < 2) {
                            $i++;
                        }

                        $this->output->progressAdvance();
                    }
                }
            }
            $this->info('');
            $this->info("Pushed {$data->count()} records to Algolia.");


            sleep(30);
        }
    }

    protected function ksortRecursive(&$array, $sort_flags = SORT_REGULAR) {
        if (!is_array($array)) {
            return false;
        }

        ksort($array, $sort_flags);

        foreach ($array as &$arr) {
            $this->ksortRecursive($arr, $sort_flags);
        }

        return true;
    }
}
