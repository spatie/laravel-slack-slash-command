<?php

namespace Spatie\SlashCommand;

use Illuminate\Support\ServiceProvider;
use Spatie\SlashCommand\SlashCommandHandler\Collection;
use Spatie\SlashCommand\SlashCommandRequest;

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

        $this->app['router']->get(config('laravel-slack-slash-command.url'), function () {

            $slashCommandRequest = SlashCommandRequest::createForRequest(request());

            $slashCommandHandlers = new Collection(config('laravel-slack-slash-command.handlers'), $slashCommandRequest);

            return $slashCommandHandlers->getResponse();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-slack-slash-command.php', 'laravel-slack-slash-command');
    }
}
