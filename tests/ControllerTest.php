<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidRequest;
use Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled;

class ControllerTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_receiving_a_request_without_a_token()
    {
        $this->expectException(InvalidRequest::class);

        $response = $this->call('POST', 'test-url');

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_throws_an_exception_when_receiving_a_wrong_token()
    {
        $this->expectException(InvalidRequest::class);

        $response = $this->call('POST', 'test-url', ['token' => 'wrong token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_can_handle_an_array_of_configured_tokens()
    {
        $this->app['config']->set('laravel-slack-slash-command.token', ['token1', 'token2']);

        $response = $this->call('POST', 'test-url', ['token' => 'token2']);

        if (isset($response->exception)) {
            throw $response->exception;
        }

        $response->assertSuccessful();
    }

    /** @test */
    public function it_throws_an_exception_if_no_handler_can_handle_the_request()
    {
        $this->app['config']->set('laravel-slack-slash-command.handlers', []);

        $this->expectException(RequestCouldNotBeHandled::class);

        $response = $this->call('POST', 'test-url', ['token' => 'test-token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_throws_an_exception_if_an_non_existing_handler_class_is_given()
    {
        $this->app['config']->set('laravel-slack-slash-command.handlers', ['NonExistingClassName']);

        $this->expectException(InvalidHandler::class);

        $response = $this->call('POST', 'test-url', ['token' => 'test-token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }
}
