<?php

namespace Spatie\SlashCommand;

use Illuminate\Support\ServiceProvider;
use Spatie\SlashCommand\SlashCommandHandler\Collection;

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

        collect(config('laravel-slack-slash-command.commands'))->each(function (array $commandConfig) {
            
            $this->app['router']->post($commandConfig['url'], function () use ($commandConfig) {

                return (new SlackCommandResponder($commandConfig, request()))->getResponse();
                
            });
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
