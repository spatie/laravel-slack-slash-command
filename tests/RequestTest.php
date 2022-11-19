<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Request;

function getPostParameters(): array
{
    return [
        'token' => 'test-token',
        'team_id' => 'T123',
        'team_domain' => 'Company',
        'channel_id' => 'C123',
        'channel_name' => 'General',
        'user_id' => 'U123',
        'user_name' => 'Bob',
        'command' => 'my-command',
        'text' => 'this is the text',
        'response_url' => 'https://slack.com/respond',
    ];
}

beforeEach(function () {
    $illuminateRequest = $this->getIlluminateRequest(getPostParameters());

    $this->request = Request::createFromIlluminateRequest($illuminateRequest);
});

it('can get the parameters as public properties', function () {
    $this->assertSame(getPostParameters()['token'], $this->request->token);
});

it('provides a get function to retrieve parameters', function () {
    $this->assertSame(getPostParameters()['token'], $this->request->get('token'));
});

it('return an empty string when getting a non existing property', function () {
    $this->assertSame('', $this->request->get('does not exist'));
});

it('can get all parameters at once', function () {
    $this->assertSame(getPostParameters()['token'], $this->request->all()['token']);
});
