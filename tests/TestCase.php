<?php

namespace PragmaRX\CountriesLaravel\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Illuminate\Cache\CacheServiceProvider::class,
            \PragmaRX\CountriesLaravel\Package\ServiceProvider::class,
        ];
    }
}
