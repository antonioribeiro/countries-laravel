<?php

namespace PragmaRX\CountriesLaravel\Tests\Service;

use PragmaRX\CountriesLaravel\Tests\TestCase;
use PragmaRX\CountriesLaravel\Package\Facade as Countries;

class CountriesTest extends TestCase
{
    public function testCountriesIsInstantiable()
    {
        $country = Countries::where('name.common', 'Brazil')->first();

        $this->assertEquals($country->name->common, 'Brazil');
    }

    public function testCountryHydration()
    {
        $country = Countries::where('name.common', 'Italy')->first()->hydrateBorders();

        $this->assertEquals($country->name->common, 'Italy');

        $this->assertEquals(6, $country->borders->count());

        $this->assertEquals('Austria', $country->borders->first()->name->common);
    }

    public function testRoutes()
    {
        $crawler = $this->call('GET', 'pragmarx/countries/flag/file/usa.svg');

        $this->assertEquals(false, $crawler->getContent());

        $crawler = $this->call('GET', 'pragmarx/countries/flag/download/usa.svg');

        $this->assertEquals(false, $crawler->getContent());

        $crawler = $this->call('GET', 'pragmarx/countries/flag/file/xxx.svg');

        $this->assertStringStartsWith('<!DOCTYPE', $crawler->getContent());
    }

    public function testCountEverything()
    {
        config(['countries.cache.enabled' => false]);

        ini_set('memory_limit', '2048M');

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

        $results = coollect($results)->sortBy(function ($country) {
            return $country[1];
        });

        $this->assertEquals($results->toArray(), [
            'taxes' => 33,
            'geometry map' => 250,
            'topology map' => 250,
            'currencies' => 267,
            'countries' => 267,
            'timezones' => 425,
            'borders' => 649,
            'flags' => 1842,
            'states' => 4526,
            'cities' => 7393,
            'timezones times' => 79594,
        ]);
    }
}
