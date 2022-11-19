<?php

namespace Spatie\SlashCommand\Test;

use GuzzleHttp\Client;
use Mockery;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

function getPayload(array $mergeVariables = []): array
{
    return array_merge([
        'text' => '',
        'channel' => 'General',
        'link_names' => true,
        'unfurl_links' => true,
        'unfurl_media' => true,
        'mrkdwn' => true,
        'response_type' => 'ephemeral',
        'attachments' => [],
    ], $mergeVariables);
}

beforeEach(function () {
    $this->responseUrl = 'https://slack.com/respond';

    $illuminateRequest = $this->getIlluminateRequest([
        'token' => 'test-token',
        'team_id' => 'T123',
        'team_domain' => 'Company',
        'channel_id' => 'C123',
        'channel_name' => 'General',
        'user_id' => 'U123',
        'user_name' => 'Bob',
        'command' => 'my-command',
        'text' => 'this is the text',
        'response_url' => $this->responseUrl,
    ]);

    $this->request = Request::createFromIlluminateRequest($illuminateRequest);

    $this->client = Mockery::spy(Client::class);

    $this->response = new Response($this->client, $this->request);
});

it('provides a factory method', function () {
    $response = Response::create($this->request);

    expect($response)->toBeInstanceOf(Response::class);
});

it('can send a text', function () {
    $this->response
        ->withText('hello')
        ->send();

    $expectedPayload = getPayload(['text' => 'hello']);

    $this->client
        ->shouldHaveReceived('post')
        ->with($this->responseUrl, ['json' => $expectedPayload]);
});

it('can send a response to a specific channel', function () {
    $this->response
        ->withText('hello')
        ->onChannel('myChannel')
        ->send();

    $expectedPayload = getPayload([
        'text' => 'hello',
        'channel' => 'myChannel',
    ]);

    $this->client
        ->shouldHaveReceived('post')
        ->with($this->responseUrl, ['json' => $expectedPayload]);
});

it('can send a response to all members on a channel', function () {
    $this->response
        ->withText('hello')
        ->displayResponseToEveryoneOnChannel('yetAnotherChannel')
        ->send();

    $expectedPayload = getPayload([
        'text' => 'hello',
        'channel' => 'yetAnotherChannel',
        'response_type' => 'in_channel',
    ]);

    $this->client
        ->shouldHaveReceived('post')
        ->with($this->responseUrl, ['json' => $expectedPayload]);
});
