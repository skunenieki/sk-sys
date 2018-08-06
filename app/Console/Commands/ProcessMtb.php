<?php

namespace Skunenieki\System\Console\Commands;

use Exception;
use AlgoliaSearch\Client;
use Illuminate\Console\Command;
use Skunenieki\System\Models\Mtb;
use Skunenieki\System\Models\MtbFinishTime;
use Skunenieki\System\Models\MtbFinishNumber;

class ProcessMtb extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'process:mtb {--once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Processor for MTB';

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
            'eventYear' => 2018,
        ];

        while (true) {
            $numbers = MtbFinishNumber::where('eventYear', $event->eventYear)->orderBy('id', 'asc')->get();
            $times   = MtbFinishTime::where('eventYear', $event->eventYear)->where('disabled', false)->orderBy('id', 'asc')->get();

            $matches = [];
            foreach ($times as $order => $time) {
                if (true === isset($numbers[$order])) {
                    $matches[] = [
                        'number' => $numbers[$order]->number,
                        'finish' => $time->finish,
                    ];

                    $mtb = Mtb::where('eventYear', $event->eventYear)->where('number', $numbers[$order]->number)->first();
                    if (null !== $mtb) {
                        $mtb->finish = $time->finish;
                        $mtb->save();
                    }
                } else {
                    $matches[] = [
                        'number' => false,
                        'finish'   => $time->finish,
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
