<?php

namespace PragmaRX\CountriesLaravel\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PragmaRX\CountriesLaravel\Package\ServiceProvider as CountriesServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CountriesServiceProvider::class,
        ];
    }
}
