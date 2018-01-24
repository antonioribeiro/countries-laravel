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

    public function testCountEverything()
    {
        $results = [
            'countries'       => 0,
            'borders'         => 0,
            'cities'          => 0,
            'currencies'      => 0,
            'flags'           => 0,
            'geometry map'    => 0,
            'states'          => 0,
            'taxes'           => 0,
            'timezones'       => 0,
            'timezones times' => 0,
            'topology map'    => 0,
        ];

        Countries::all()->map(function ($country) use (&$results) {
            $results['countries']++;

            $results['borders'] = $results['borders'] + $country->hydrate('borders')->borders->count();

            $results['cities'] = $results['cities'] + $country->hydrate('cities')->cities->count();

            $results['currencies'] = $results['currencies'] + $country->hydrate('currencies')->currencies->count();

            $results['flags'] = $results['flags'] + $country->hydrate('flag')->flag->count();

            $results['states'] = $results['states'] + $country->hydrate('states')->states->count();

            $results['timezones'] = $results['timezones'] + $country->hydrate('timezones')->timezones->count();

            $results['taxes'] = $results['taxes'] + $country->hydrate('taxes')->taxes->count();

            $results['geometry map'] = $results['geometry map'] + ((string) $country->hydrate('geometry')->geometry == '' ? 0 : 1);

            $results['topology map'] = $results['topology map'] + ((string) $country->hydrate('topology')->topology == '' ? 0 : 1);

            $country = $country->hydrate(['timezones', 'timezones_times']);

            $results['timezones times'] = $results['timezones times'] + $country->timezones->reduce(function ($carry, $timezone) {
                return $timezone->times->count();
            }, 0);
        });

        $results = collect($results)->sortBy(function ($country) {
            return $country[1];
        });

        $this->assertEquals($results->toArray(), [
            'taxes' => 32,
            'geometry map' => 248,
            'topology map' => 248,
            'currencies' => 256,
            'countries' => 266,
            'timezones' => 423,
            'borders' => 649,
            'flags' => 1570,
            'states' => 4526,
            'cities' => 7376,
            'timezones times' => 81153,
        ]);
    }
}
