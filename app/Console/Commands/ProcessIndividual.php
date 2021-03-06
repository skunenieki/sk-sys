<?php

namespace Skunenieki\System\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\IndividualFinishTime;
use Skunenieki\System\Models\IndividualFinishNumber;

class ProcessIndividual extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'process:individual {--once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Processor for Individual';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $event = (object) [
            'eventYear' => 2020, // @todo 2020
        ];

        while (true) {
            $numbers = IndividualFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
            $times   = IndividualFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

            $matches = [];
            foreach ($times as $order => $time) {
                if (true === isset($numbers[$order])) {
                    $matches[] = [
                        'number' => $numbers[$order]->number,
                        'finish' => $time->finish,
                    ];

                    $ind = Individual::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                    if (null !== $ind) {
                        $ind->finish = $time->finish;
                        $ind->save();
                    }
                } else {
                    $matches[] = [
                        'number' => false,
                        'finish' => $time->finish,
                    ];
                }
            }

            if ($this->option('once') === true) {
                break;
            }

            sleep(3);
        }
    }
}
