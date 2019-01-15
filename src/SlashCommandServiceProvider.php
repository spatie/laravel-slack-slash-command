<?php

namespace Spatie\SlashCommand;

use Illuminate\Support\ServiceProvider;
use Spatie\SlashCommand\Middleware\VerifySlackRequest;

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

        $this->app['router']->aliasMiddleware('verify' , VerifySlackRequest::class);
        $this->app['router']
            ->post(config('laravel-slack-slash-command')['url'], Controller::class.'@getResponse')
            ->middleware('verify');

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-slack-slash-command.php', 'laravel-slack-slash-command');
    }
}
