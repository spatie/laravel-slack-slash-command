<?php

namespace Spatie\SlashCommand;

use Illuminate\Support\ServiceProvider;

class SlashCommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-slack-slash-command.php' => config_path('laravel-slack-slash-command.php'),
        ], 'config');

        $this->addRoutes();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-slack-slash-command.php', 'laravel-slack-slash-command');
    }

    /**
     * Define routes.
     */
    public function addRoutes()
    {
        $router = $this->app['router'];

        $domain = array_key_exists('domain', config('laravel-slack-slash-command')) ? config('laravel-slack-slash-command')['domain'] : '';

        $router->domain($domain)->get(config('laravel-slack-slash-command')['path'], Controller::class.'@getResponse');
    }
}
