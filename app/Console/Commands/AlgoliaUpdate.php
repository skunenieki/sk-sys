<?php

namespace Skunenieki\System\Console\Commands;

use Algolia\AlgoliaSearch\SearchClient;
use Exception;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Individual;

class AlgoliaUpdate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'algolia:update {--sleep=60} {--once} {--hide-current-year : Do not push group results of current year.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates records in Algolia index, but only those participants that take place in this years event.';

    public function __construct()
    {
        parent::__construct();

        $this->algolia = SearchClient::create(
            env('ALGOLIA_APP_ID'),
            env('ALGOLIA_SECRET'),
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $eventYear = 2019; // @todo 2019

        $thisYearsFinished = Individual::where('eventYear', $eventYear)
              ->whereNotNull('finish')->get();

        if ($thisYearsFinished->count() < 1) {
            $this->output->write('Found zero finishers this year, exiting!');
            return 0;
        }

        $index = $this->algolia->initIndex('skunenieki');

        while (true) {
            $data = Individual::whereNotNull('finish');

            $results  = [];
            $results2 = [];
            foreach ($data->get() as $individual) {
                $results[$individual->eventYear][$individual->gender][$individual->group][$individual->resultInSeconds][$individual->id] = $individual;
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
                                    'participantId' => $individual->participantId,
                                    'name'          => $individual->name,
                                    'birthYear'     => $individual->birthYear,
                                    'result'        => $individual->result,
                                    'group'         => $individual->group,
                                    'rankInGroup'   => (true === $this->option('hide-current-year') && $individual->eventYear === $eventYear) ? '-' : $i,
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

            $all = [];
            foreach ($results2 as $currentEventYear => $data) {
                foreach ($data as $groups) {
                    $i = 1;
                    foreach ($groups as $resultsInSec) {
                        $last = end($resultsInSec);
                        foreach ($resultsInSec as $individual) {
                            $last['rankInSummary']       = $i;
                            $individual['rankInSummary'] = (true === $this->option('hide-current-year') && $currentEventYear === $eventYear) ? '-' : $i;

                            $all[] = $individual;

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

            $thisYearsGrouped = [];
            foreach ($thisYearsFinished as $individual) {
                if (isset($grouped[$individual->participantId])) {
                    $thisYearsGrouped[$individual->participantId] = $grouped[$individual->participantId];
                }
            }

            $count = count($thisYearsGrouped);

            $index->saveObjects($thisYearsGrouped);

            $this->info("\nPushed {$count} records to Algolia.\n");

            if ($this->option('once') === true) {
                break;
            }

            sleep((int) $this->option('sleep'));
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
