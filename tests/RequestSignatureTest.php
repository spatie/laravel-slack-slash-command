<?php

namespace Spatie\SlashCommand\Test;

use Illuminate\Http\Request;
use Spatie\SlashCommand\RequestSignature;

function getHeaders(): array
{
    return [
        'X-Slack-Request-Timestamp' => 1234,
    ];
}

function getIlluminateRequest($values, $headers = []): Request
{
    $illuminateRequest = new Request($values);

    $illuminateRequest->headers->add($headers);

    return $illuminateRequest;
}

beforeEach(function () {
    $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test-signing');

    $this->requestSignature = new RequestSignature();
});

it('can create new signature', function () {
    $illuminateRequest = getIlluminateRequest($this->getPostParametersForSignature(), getHeaders());

    $signature = $this->requestSignature->create($illuminateRequest);

    expect($signature)->toBe($this->getTestSignature());
});

it('cannot create new signature with invalid timestamp', function () {
    $headers = [
        'X-Slack-Request-Timestamp' => 1111,
    ];

    $illuminateRequest = getIlluminateRequest($this->getPostParametersForSignature(), $headers);

    $signature = $this->requestSignature->create($illuminateRequest);

    expect($signature)->not->toBe($this->getTestSignature());
});

it('cannot create new signature with invalid signing secret', function () {
    $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test1-signing');

    $illuminateRequest = getIlluminateRequest($this->getPostParametersForSignature(), getHeaders());

    $signature = $this->requestSignature->create($illuminateRequest);

    expect($signature)->not->toBe($this->getTestSignature());
});

it('cannot create new signature with invalid post parameters', function () {
    $illuminateRequest = getIlluminateRequest([], getHeaders());

    $signature = $this->requestSignature->create($illuminateRequest);

    expect($signature)->not->toBe($this->getTestSignature());
});
