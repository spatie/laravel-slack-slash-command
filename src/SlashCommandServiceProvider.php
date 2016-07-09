<?php

namespace Spatie\SlashCommand;

use Illuminate\Support\ServiceProvider;
use Spatie\SlashCommand\SlashCommandHandler\BaseHandler;
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
                
                if (!request()->has('token')) {
                    throw InvalidSlashCommandRequest::tokenNotFound();
                }

                if (request()->get('token') != $commandConfig['verification_token']) {
                    throw InvalidSlashCommandRequest::invalidToken(request()->get('token'));
                }

                $handler = collect($commandConfig['handlers'])
                    ->map(function(string $handlerClassName) {
                       return new $handlerClassName(request());
                    })
                    ->filter(function(BaseHandler $handler) {
                        return $handler->canHandleCurrentRequest();
                    })->first();

                if (!$handler) {
                    throw RequestCouldNotBeProcessed::noHandlerFound(request());
                }

                $response = $handler->handleCurrentRequest();

                return $response->finalize();
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
