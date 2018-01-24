<?php

namespace PragmaRX\CountriesLaravel\Tests\Service;

use Illuminate\Support\Facades\Validator;
use PragmaRX\CountriesLaravel\Tests\TestCase;

class ValidationTest extends TestCase
{
    public function testCommonNameRule()
    {
        // Valid country
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make(['country' => 'Brazil'], ['country' => 'country']);
        $this->assertTrue($validator->passes());

        // Valid country
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make(['country' => 'Brazil'], ['country' => 'nameCommon']);
        $this->assertTrue($validator->passes());

        // Valid country
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make(['country' => 'Brazil'], ['country' => 'name']);
        $this->assertTrue($validator->passes());

        // Change to Invalid country
        $validator->setData(['country' => 'NotACountry']);
        $this->assertFalse($validator->passes());
    }

    public function testISOA3Rule()
    {
        // Valid country
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make(['country' => 'BRA'], ['country' => 'cca3']);
        $this->assertTrue($validator->passes());

        // Change to Invalid country
        $validator->setData(['country' => 'NotACountry']);
        $this->assertFalse($validator->passes());
    }

    public function testCurrency()
    {
        // Valid currency
        $validator = Validator::make(['country' => 'EUR'], ['country' => 'currencies']);
        $this->assertTrue($validator->passes());

        // Change to invalid currency
        $validator->setData(['country' => 'NotACountry']);
        $this->assertFalse($validator->passes());
    }
}
