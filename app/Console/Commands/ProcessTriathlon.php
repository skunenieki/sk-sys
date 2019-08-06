<?php

namespace Skunenieki\System\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Triathlon;
use Skunenieki\System\Models\TriathlonTeam;
use Skunenieki\System\Models\TriathlonFinishTime;
use Skunenieki\System\Models\TriathlonFinishNumber;

class ProcessTriathlon extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'process:triathlon {--once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Processor for Triathlon';

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
            'eventYear' => 2019, // @todo 2019
        ];

        while (true) {
            $numbers = TriathlonFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
            $times   = TriathlonFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

            $matches = [];
            foreach ($times as $order => $time) {
                if (true === isset($numbers[$order])) {
                    $matches[] = [
                        'number' => $numbers[$order]->number,
                        'finish' => $time->finish,
                    ];

                    $tri = Triathlon::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();

                    if (null === $tri) {
                        $tri = TriathlonTeam::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                    }

                    if (null !== $tri) {
                        $tri->finish = $time->finish;
                        $tri->save();
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
