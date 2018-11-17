<?php

namespace Bor3y\ApiTemplate;

use Bor3y\ApiTemplate\Commands\Test;
use Illuminate\Support\ServiceProvider;

class ApiTemplateServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bor3y');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'bor3y');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/apitemplate.php', 'apitemplate');

        // Register the service the package provides.
        $this->app->singleton('apitemplate', function ($app) {
            return new ApiTemplate;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['apitemplate'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/apitemplate.php' => config_path('apitemplate.php'),
        ], 'apitemplate.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bor3y'),
        ], 'apitemplate.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/bor3y'),
        ], 'apitemplate.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/bor3y'),
        ], 'apitemplate.views');*/

        // Registering package commands.
        $this->commands([
            Test::class
        ]);
    }
}
