<?php

namespace Spatie\SlashCommand\Test;

use Illuminate\Contracts\Console\Kernel;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Spatie\SlashCommand\SlashCommandServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $commandConfig = [
            'name' => 'test-name',
            'url' => 'test-url',
            'token' => 'test-token',
            'handlers' => [
                \Spatie\SlashCommand\SlashCommandHandler\CatchAll::class
            ],
        ];

        $app['config']->set('laravel-slack-slash-command.commands', [$commandConfig]);
    }
}
