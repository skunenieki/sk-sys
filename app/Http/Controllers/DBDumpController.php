<?php

namespace Skunenieki\System\Http\Controllers;

use mysqli;
use MySQLDump;

class DBDumpController extends Controller
{
    public function dump()
    {
        $url = parse_url(env('JAWSDB_MARIA_URL'));
        $dump = new MySQLDump(new mysqli($url['host'], $url['user'], $url['pass'], substr($url['path'], 1)));

        return $dump->write();
    }
}
