<?php

namespace PragmaRX\CountriesLaravel\Package;

use Illuminate\Support\Facades\Validator;
use PragmaRX\Coollection\Package\Coollection;
use PragmaRX\CountriesLaravel\Package\Facade as CountriesFacade;
use PragmaRX\Countries\Package\Countries as CountriesService;
use PragmaRX\Countries\Package\Services\Config;
use PragmaRX\Countries\Package\Services\Helper;
use PragmaRX\Countries\Update\Updater;
use PragmaRX\Countries\Package\Services\Hydrator;
use PragmaRX\CountriesLaravel\Package\Console\Commands\Update;
use PragmaRX\Countries\Package\Data\Repository;
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
            __COUNTRIES_DIR__.$this->helper->toDir('/src/config/countries.php') => config_path('countries.php'),
        ], 'config');
    }

    /**
     * Create the collection hydrator macro.
     */
    private function createCollectionHydrator()
    {
        Coollection::macro('hydrate', function ($elements) {
            return Countries::hydrate($this, $elements);
        });

        foreach (Hydrator::HYDRATORS as $hydrator) {
            $hydrator = 'hydrate'.studly_case($hydrator);

            Coollection::macro($hydrator, function () use ($hydrator) {
                return Countries::getRepository()->getHydrator()->{$hydrator}($this);
            });
        }
    }

    /**
     * Define global constant __COUNTRIES_DIR__.
     */
    protected function definePath()
    {
        if (! defined('__COUNTRIES_DIR__')) {
            define(
                '__COUNTRIES_DIR__',
                realpath(
                    __DIR__.$this->helper->toDir('/../../../countries')
                )
            );
        }
    }

    /**
     * Merge configuration.
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.$this->helper->toDir('/../config/countries.php'), 'countries'
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
        $this->definePath();

        $this->configurePaths();

        $this->mergeConfig();

        $this->registerService();

        $this->registerUpdateCommand();

        $this->createCollectionHydrator();
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
