<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidRequest;
use Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled;

class ControllerTest extends TestCase
{
    const TEST_URL = 'test-url';

    /** @test */
    public function it_throws_an_exception_when_receiving_a_request_without_a_token()
    {
        $this->expectException(InvalidRequest::class);

        $response = $this->post(self::TEST_URL);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_throws_an_exception_when_receiving_a_wrong_token()
    {
        $this->expectException(InvalidRequest::class);

        $response = $this->post(self::TEST_URL, ['token' => 'wrong token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_can_handle_an_array_of_configured_tokens()
    {
        $this->app['config']->set('laravel-slack-slash-command.token', ['token1', 'token2']);

        $response = $this->post(self::TEST_URL, ['token' => 'token2']);

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

        $response = $this->post(self::TEST_URL, ['token' => 'test-token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_throws_an_exception_if_an_non_existing_handler_class_is_given()
    {
        $this->app['config']->set('laravel-slack-slash-command.handlers', ['NonExistingClassName']);

        $this->expectException(InvalidHandler::class);

        $response = $this->post(self::TEST_URL, ['token' => 'test-token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_throws_an_exception_when_receiving_a_wrong_signature()
    {
        $this->app['config']->set('laravel-slack-slash-command.verify_with_signing', true);

        $this->expectException(InvalidRequest::class);

        $response = $this->post(self::TEST_URL, ['token' => 'test-token']);

        if (isset($response->exception)) {
            throw $response->exception;
        }
    }

    /** @test */
    public function it_can_verify_request_with_signature()
    {
        $this->app['config']->set('laravel-slack-slash-command.verify_with_signing', true);
        $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test-signing');

        $signature = $this->getTestSignature();

        $requestData = [
            'token' => 'test-token',
            'user_id' => 'U123'
        ];

        $headers = [
            'X-Slack-Request-Timestamp' => 1234,
            'X-Slack-Signature' => $signature
        ];

        $response = $this->post(self::TEST_URL, $requestData, $headers);

        $response->assertSuccessful();
    }
}
