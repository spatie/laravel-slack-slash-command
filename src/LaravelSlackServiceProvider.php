<?php

namespace Spatie\Slashcommand;

use Illuminate\Support\ServiceProvider;
use Spatie\Slashcommand\SlashCommandHandler\Collection;
use Spatie\Slashcommand\SlashCommandRequest;

class LaravelSlackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-slack.php' => config_path('laravel-slack.php'),
        ], 'config');

        $this->app['router']->get(config('laravel-slack.slash_command_url'), function () {

            $slashCommandRequest = SlashCommandRequest::createForRequest(request());

            $slashCommandHandlers = new Collection(config('laravel-slack.slash_command_handlers'), $slashCommandRequest);

            return $slashCommandHandlers->getResponse();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-slack.php', 'laravel-slack');
    }
}
