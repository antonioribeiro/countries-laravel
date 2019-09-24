<?php

namespace PragmaRX\CountriesLaravel\Package\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use PragmaRX\CountriesLaravel\Package\Facade as CountriesFacade;

class Flag extends Controller
{
    /**
     * Find a country by its cca3 code and hydrate flag.
     *
     * @param $cca3
     * @return null
     */
    private function findCountryByCca3($cca3)
    {
        if (is_null($country = CountriesFacade::where('cca3', Str::upper($cca3)))) {
            abort(404);
        }

        return $country->first()->hydrateFlag();
    }

    /**
     * Download flag action.
     *
     * @param $cca3
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($cca3)
    {
        return response()->download($this->getFlagPath($cca3));
    }

    /**
     * File flag action.
     *
     * @param $cca3
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($cca3)
    {
        return response()->file($this->getFlagPath($cca3));
    }

    /**
     * Get the flag path.
     *
     * @param $cca3
     * @return mixed
     */
    protected function getFlagPath($cca3)
    {
        return $this->findCountryByCca3($cca3)->flag->svg_path;
    }
}
