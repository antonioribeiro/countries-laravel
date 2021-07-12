<?php

namespace PragmaRX\CountriesLaravel\Package;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use PragmaRX\CountriesLaravel\Package\Facade as CountriesFacade;

class ValidationServiceProvider extends IlluminateServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('countries.validation.enabled')) {
            $this->addValidators();
        }
    }

    /**
     * Add validators.
     */
    protected function addValidators()
    {
        foreach (config('countries.validation.rules') as $ruleName => $countryAttribute) {
            if (is_int($ruleName)) {
                $ruleName = $countryAttribute;
            }

            Validator::extend($ruleName, function ($attribute, $value) use ($countryAttribute) {
                return ! CountriesFacade::where($countryAttribute, $value)->isEmpty();
            }, 'The :attribute must be a valid '.$ruleName.'.');
        }
    }
}