<?php

namespace Spatie\SlashCommand\Test;

use Mockery;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Http\Request as IlluminateRequest;

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

        $app['config']->set('laravel-slack-slash-command', [
            'token' => 'test-token',
            'url' => 'test-url',
            'handlers' => [
                \Spatie\SlashCommand\Handlers\CatchAll::class,
            ],
        ]);
    }

    public function getIlluminateRequest($values): IlluminateRequest
    {
        $mock = Mockery::mock(IlluminateRequest::class);

        foreach ($values as $name => $value) {
            $mock->shouldReceive('get')->withArgs([$name])->andReturn($value);
        }

        return $mock;
    }
}
