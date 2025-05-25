<?php

namespace Akashpoudelnp\NepaliDateCarbon\Tests;

use Akashpoudelnp\NepaliDateCarbon\NepaliDateCarbonServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            NepaliDateCarbonServiceProvider::class,
        ];
    }
}
