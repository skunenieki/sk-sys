<?php

namespace Skunenieki\System\Console\Commands;

use Exception;
use AlgoliaSearch\Client;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Individual;

class AlgoliaUpdate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'algolia:update {--once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates records in Algolia index, but only those participants that take place in this years event.';

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
    public function handle()
    {
        $index = $this->algolia->initIndex('skunenieki');

        while (true) {
            $data = Individual::where('eventYear', 2017) // @todo
                              ->whereNotNull('finish');

            $results  = [];
            $results2 = [];
            foreach ($data->get() as $participant) {
                $fromAllYears = Individual::whereNotNull('finish')->where('participantId', $participant->participantId)->get();

                foreach ($fromAllYears as $individual) {
                    $results[$individual->eventYear][$individual->gender][$individual->group][$individual->resultInSeconds][$individual->id] = $individual;
                }
            }

            $this->ksortRecursive($results);

            foreach ($results as $eventYears) {
                foreach ($eventYears as $genders) {
                    foreach ($genders as $groups) {
                        $i = 1;
                        foreach ($groups as $resultsInSec) {
                            $last = end($resultsInSec);
                            foreach ($resultsInSec as $individual) {
                                $results2[$individual->eventYear][$individual->gender][$individual->resultInSeconds][$individual->id] = [
                                    // 'objectID'      => $individual->id,
                                    'participantId' => $individual->participantId,
                                    'name'          => $individual->name,
                                    'birthYear'     => $individual->birthYear,
                                    'result'        => $individual->result,
                                    'group'         => $individual->group,
                                    'rankInGroup'   => $i,
                                    'eventYear'     => $individual->eventYear,
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
            }

            $this->ksortRecursive($results2);

            $this->output->progressStart($data->count());

            $all = [];
            foreach ($results2 as $eventYear) {
                foreach ($eventYear as $groups) {
                    $i = 1;
                    foreach ($groups as $resultsInSec) {
                        $last = end($resultsInSec);
                        foreach ($resultsInSec as $individual) {
                            $last['rankInSummary']       = $i;
                            $individual['rankInSummary'] = $i;

                            $all[] = $individual;

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
            }

            $grouped = [];
            foreach ($all as $each) {
                if (false === isset($grouped[$each['participantId']])) {
                    $grouped[$each['participantId']] = [
                        'objectID'      => $each['participantId'],
                        'participantId' => $each['participantId'],
                        'name'          => $each['name'],
                        'birthYear'     => $each['birthYear'],
                        'results'       => [],
                    ];
                }

                $grouped[$each['participantId']]['results'][$each['eventYear']] = [
                    'result'        => $each['result'],
                    'group'         => $each['group'],
                    'rankInGroup'   => $each['rankInGroup'],
                    'rankInSummary' => $each['rankInSummary'],
                ];
            }

            $count = count($grouped);

            $index->saveObjects($grouped);

            $this->info("\nPushed {$count} records to Algolia.\n");

            if ($this->option('once') === true) {
                break;
            }

            sleep(120);
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
