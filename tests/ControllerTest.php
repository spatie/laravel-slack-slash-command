<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidRequest;
use Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled;

beforeEach(function () {
    $this->TEST_URL = 'test-url';
});

it('throws an exception when receiving a request without a token', function () {
    $response = $this->post($this->TEST_URL);

    if (isset($response->exception)) {
        throw $response->exception;
    }
})->throws(InvalidRequest::class);

it('throws an exception when receiving a wrong token', function () {
    $response = $this->post($this->TEST_URL, ['token' => 'wrong token']);

    if (isset($response->exception)) {
        throw $response->exception;
    }
})->throws(InvalidRequest::class);

it('can handle an array of configured tokens', function () {
    $this->app['config']->set('laravel-slack-slash-command.token', ['token1', 'token2']);

    $response = $this->post($this->TEST_URL, ['token' => 'token2']);

    if (isset($response->exception)) {
        throw $response->exception;
    }

    $response->assertSuccessful();
});

it('throws an exception if no handler can handle the request', function () {
    $this->app['config']->set('laravel-slack-slash-command.handlers', []);

    $response = $this->post($this->TEST_URL, ['token' => 'test-token']);

    if (isset($response->exception)) {
        throw $response->exception;
    }
})->throws(RequestCouldNotBeHandled::class);

it('throws an exception if an non existing handler class is given', function () {
    $this->app['config']->set('laravel-slack-slash-command.handlers', ['NonExistingClassName']);

    $response = $this->post($this->TEST_URL, ['token' => 'test-token']);

    if (isset($response->exception)) {
        throw $response->exception;
    }
})->throws(InvalidHandler::class);

it('throws an exception when receiving a wrong signature', function () {
    $this->app['config']->set('laravel-slack-slash-command.verify_with_signing', true);

    $response = $this->post($this->TEST_URL, ['token' => 'test-token']);

    if (isset($response->exception)) {
        throw $response->exception;
    }
})->throws(InvalidRequest::class);

it('can verify request with signature', function () {
    $this->app['config']->set('laravel-slack-slash-command.verify_with_signing', true);
    $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test-signing');

    $signature = $this->getTestSignature();

    $headers = [
        'X-Slack-Request-Timestamp' => 1234,
        'X-Slack-Signature' => $signature,
    ];

    $response = $this->post($this->TEST_URL, $this->getPostParametersForSignature(), $headers);

    $response->assertSuccessful();
});
