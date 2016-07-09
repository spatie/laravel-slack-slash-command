<?php

namespace Spatie\Slashcommand;

use Illuminate\Support\ServiceProvider;
use Spatie\Slashcommand\SlashCommandHandler\Collection;
use Spatie\Slashcommand\SlashCommandRequest;

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
