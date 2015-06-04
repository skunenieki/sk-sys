<?php

namespace Skunenieki\System\Console\Commands;

use DB;
use Exception;
use SqlFormatter;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetDBSnapshot extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchoronize database by getting stapshot from production';

    public function __construct(Client $guzzle)
    {
        parent::__construct();

        $this->guzzle = $guzzle;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $login    = $this->ask('Login');
        $password = $this->secret('Password');

        $dumpUrl = env('APP_PRODUCTION_URL').'/dbdump';

        $this->info("Getting DB dump from {$dumpUrl}");

        try {
            $response = $this->guzzle->get($dumpUrl, ['auth' => [$login, $password]]);

            if (200 == $response->getStatusCode()) {
                $this->info('Running DB synchronization...');
                $queries = SqlFormatter::splitQuery((string) $response->getBody());

                foreach ($queries as $query) {
                    DB::statement($query);
                }
                $this->info('Databse synchronization completed!');
                return;
            } else {
                throw new Exception('Failed to get response, status code was ' . $response->getStatusCode());
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
            $this->error('ERROR: Something went wrong!');
        }
    }
}
