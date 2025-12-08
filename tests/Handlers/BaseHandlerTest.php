<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Handlers\BaseHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

function getPostParametersForBaseHandler(array $mergeVariables = []): array
{
    return array_merge([
        'token' => 'test-token',
        'team_id' => 'T123',
        'team_domain' => 'Company',
        'channel_id' => 'C123',
        'channel_name' => 'General',
        'user_id' => 'U123',
        'user_name' => 'Bob',
        'command' => '/commandName',
        'text' => 'handlerName my-argument --option',
        'response_url' => 'https://slack.com/respond',
    ], $mergeVariables);
}

beforeEach(function () {
    $illuminateRequest = $this->getIlluminateRequest(getPostParametersForBaseHandler());

    $this->request = Request::createFromIlluminateRequest($illuminateRequest);
});

it('can handle requests if allowed', function () {
    $baseHandler = new class($this->request) extends BaseHandler {
        public function canHandle(Request $request): bool
        {
            return true;
        }

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    expect($baseHandler->canHandle($this->request))->toBeTrue();
});

it('cannot handle requests if disallowed', function () {
    $baseHandler = new class($this->request) extends BaseHandler {
        public function canHandle(Request $request): bool
        {
            return false;
        }

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    expect($baseHandler->canHandle($this->request))->toBeFalse();
});

it('can respond with text', function () {
    $baseHandler = new class($this->request) extends BaseHandler {
        public function canHandle(Request $request): bool
        {
            return true;
        }

        public function handle(Request $request): Response
        {
            return $this->respondToSlack('Testing 123');
        }
    };

    $response = $baseHandler->handle($this->request)->getIlluminateResponse();

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBeJson();
    expect(json_decode($response->getContent(), associative: true)['text'])->toBe('Testing 123');
});

it('can respond with an empty body', function () {
    $baseHandler = new class($this->request) extends BaseHandler {
        public function canHandle(Request $request): bool
        {
            return true;
        }

        public function handle(Request $request): Response
        {
            return $this->acknowledgeToSlack();
        }
    };

    $response = $baseHandler->handle($this->request)->getIlluminateResponse();

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBeString();
    expect($response->getContent())->toBeEmpty();
});
