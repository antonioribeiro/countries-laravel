<?php

namespace PragmaRX\CountriesLaravel\Package;

use Illuminate\Support\Facades\Validator;
use PragmaRX\Countries\Package\Data\Repository;
use PragmaRX\Countries\Package\Services\Config;
use PragmaRX\Countries\Package\Services\Helper;
use PragmaRX\Countries\Package\Services\Hydrator;
use PragmaRX\Countries\Package\Countries as CountriesService;
use PragmaRX\CountriesLaravel\Package\Console\Commands\Update;
use PragmaRX\CountriesLaravel\Package\Facade as CountriesFacade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \PragmaRX\CountriesLaravel\Package\Support\Helper
     */
    protected $helper;

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->helper = new Helper(new Config());
    }

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
        if (config('countries.validation.enabled')) {
            $this->addValidators();
        }
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
    }

    /**
     * Register the service.
     */
    protected function registerService()
    {
        $this->app->singleton('pragmarx.countries.cache', $cache = app(config('countries.cache.service')));

        $this->app->singleton('pragmarx.countries', function () use ($cache) {
            $hydrator = new Hydrator($config = new Config());

            $repository = new Repository($cache, $hydrator, $this->helper, $config);

            $hydrator->setRepository($repository);

            return new CountriesService($config, $cache, $this->helper, $hydrator, $repository);
        });
    }

    /**
     * Add validators.
     */
    private function addValidators()
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

    /**
     * Register update command.
     */
    private function registerUpdateCommand()
    {
        $this->app->singleton($command = 'countries.update.command', function () {
            return new Update();
        });

        $this->commands($command);
    }
}
