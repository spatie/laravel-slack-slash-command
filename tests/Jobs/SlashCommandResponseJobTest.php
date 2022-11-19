<?php

namespace Spatie\SlashCommand\Test\Jobs;

use Spatie\SlashCommand\Jobs\SlashCommandResponseJob;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

function getPostParameters(array $mergeVariables = []): array
{
    return array_merge([
        'token' => 'test-token',
        'team_id' => 'T123',
        'team_domain' => 'Company',
        'channel_id' => 'C123',
        'channel_name' => 'General',
        'user_id' => 'U123',
        'user_name' => 'Bob',
        'command' => 'command',
        'text' => 'my-argument --option',
        'response_url' => 'https://slack.com/respond',
    ], $mergeVariables);
}

beforeEach(function () {
    $illuminateRequest = $this->getIlluminateRequest(getPostParameters());

    $this->request = Request::createFromIlluminateRequest($illuminateRequest);

    $this->job = new class() extends SlashCommandResponseJob {
        public function handle()
        {
        }
    };

    $this->job->setRequest($this->request);
});

it('can get a request', function () {
    expect($this->request)->toBeInstanceOf(Request::class);
    expect($this->job->getRequest())->toBeInstanceOf(Request::class);
});

it('can get a response', function () {
    expect($this->job->getResponse())->toBeInstanceOf(Response::class);
});
