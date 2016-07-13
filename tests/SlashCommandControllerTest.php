<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidRequest;
use Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled;

class SlashCommandControllerTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_receiving_a_request_without_a_token()
    {
        $this->expectException(InvalidRequest::class);

        $this->call('POST', 'test-url');
    }

    /** @test */
    public function it_throws_an_exception_when_receiving_a_wrong_token()
    {
        $this->expectException(InvalidRequest::class);

        $this->call('POST', 'test-url', ['token' => 'wrong token']);
    }

    /** @test */
    public function it_throws_an_exception_if_no_handler_can_handle_the_request()
    {
        $this->app['config']->set('laravel-slack-slash-command.handlers', []);

        $this->expectException(RequestCouldNotBeHandled::class);

        $this->call('POST', 'test-url', ['token' => 'test-token']);
    }

    /** @test */
    public function it_throws_an_exception_if_an_non_existing_handler_class_is_given()
    {
        $this->app['config']->set('laravel-slack-slash-command.handlers', ['NonExistingClassName']);

        $this->expectException(InvalidHandler::class);

        $this->call('POST', 'test-url', ['token' => 'test-token']);
    }
}
