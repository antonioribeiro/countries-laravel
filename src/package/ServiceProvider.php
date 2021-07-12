<?php

namespace PragmaRX\CountriesLaravel\Package;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use PragmaRX\Countries\Package\Countries as CountriesService;
use PragmaRX\Countries\Package\Data\Repository;
use PragmaRX\Countries\Package\Services\Cache\Service as Cache;
use PragmaRX\Countries\Package\Services\Config;
use PragmaRX\Countries\Package\Services\Helper;
use PragmaRX\Countries\Package\Services\Hydrator;
use PragmaRX\CountriesLaravel\Package\Console\Commands\Update;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $defer = true;

    /**
     * Configure package paths.
     */
    protected function configurePaths()
    {
        $this->publishes([
            $this->getPackageConfigFile() => config_path('countries.php'),
        ], 'config');
    }

    /**
     * Get the package config file path.
     *
     * @return string
     */
    protected function getPackageConfigFile()
    {
        return __DIR__.'/../config/countries.php';
    }

    /**
     * Merge configuration.
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            $this->getPackageConfigFile(), 'countries'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configurePaths();

        $this->mergeConfig();

        $this->registerService();

        $this->registerUpdateCommand();

        if (config('countries.routes.enabled')) {
            $this->registerRoutes();
        }
    }

    /**
     * Register routes.
     */
    protected function registerRoutes()
    {
        Route::get(
            '/pragmarx/countries/flag/file/{cca3}.svg',
            [
                'name' => 'pragmarx.countries.flag.file',
                'uses' => '\PragmaRX\CountriesLaravel\Package\Http\Controllers\Flag@file',
            ]
        );

        Route::get(
            '/pragmarx/countries/flag/download/{cca3}.svg',
            [
                'name' => 'pragmarx.countries.flag.download',
                'uses' => '\PragmaRX\CountriesLaravel\Package\Http\Controllers\Flag@download',
            ]
        );
    }

    /**
     * Register the service.
     */
    protected function registerService()
    {
        $this->app->singleton('pragmarx.countries', function () {
            $hydrator = new Hydrator($config = new Config(config()));

            $cache = new Cache($config, app(config('countries.cache.service')));

            $helper = new Helper($config);

            $repository = new Repository($cache, $hydrator, $helper, $config);

            $hydrator->setRepository($repository);

            return new CountriesService($config, $cache, $helper, $hydrator, $repository);
        });
    }

    /**
     * Register update command.
     */
    protected function registerUpdateCommand()
    {
        $this->app->singleton($command = 'countries.update.command', function () {
            return new Update();
        });

        $this->commands($command);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'pragmarx.countries',
            'countries.update.command',
        ];
    }
}
