<?php

namespace Skunenieki\System\Tests;

use Laravel\Lumen\Testing\TestCase as LumenTestCase;

class TestCase extends LumenTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
