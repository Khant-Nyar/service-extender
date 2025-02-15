<?php

namespace KhantNyar\ServiceExtender;

use Illuminate\Support\ServiceProvider;
use KhantNyar\ServiceExtender\Commands\ServiceMakeCommand;
use Illuminate\Support\Str;

class ServiceExtenderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'service-extender');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'service-extender');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('service-extender.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/service-extender'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/service-extender'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/service-extender'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([ServiceMakeCommand::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/extender.php', 'service-extender');
        // Register the main class to use with the facade
        $this->app->singleton('service-extender', function () {
            return new ServiceExtender;
        });
    }
}
