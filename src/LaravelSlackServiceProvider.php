<?php

namespace Spatie\Skeleton;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSlack\SlashCommandHandler\Collection;
use Spatie\LaravelSlack\SlashCommandRequest;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-slack.php' => config_path('laravel-slack.php'),
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
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-slack.php', 'laravel-slack');
    }
}
