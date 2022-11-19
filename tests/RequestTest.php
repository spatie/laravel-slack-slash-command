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
    expect($this->request->token)->toBe(getPostParameters()['token']);
});

it('provides a get function to retrieve parameters', function () {
    expect($this->request->get('token'))->toBe(getPostParameters()['token']);
});

it('return an empty string when getting a non existing property', function () {
    expect($this->request->get('does not exist'))->toBe('');
});

it('can get all parameters at once', function () {
    expect($this->request->all()['token'])->toBe(getPostParameters()['token']);
});
