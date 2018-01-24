<?php

namespace PragmaRX\CountriesLaravel\Tests\Service;

use PragmaRX\CountriesLaravel\Tests\TestCase;
use PragmaRX\CountriesLaravel\Package\Facade as Countries;

class CountriesTest extends TestCase
{
    public function testCountriesIsInstantiable()
    {
        $brazil = Countries::where('name.common', 'Brazil')->first();

        $this->assertEquals($brazil->name->common, 'Brazil');
    }
}
