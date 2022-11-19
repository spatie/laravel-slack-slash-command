<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Handlers\SignatureHandler;
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
        'command' => '/commandName',
        'text' => 'handlerName my-argument --option',
        'response_url' => 'https://slack.com/respond',
    ], $mergeVariables);
}

beforeEach(function () {
    $illuminateRequest = $this->getIlluminateRequest(getPostParameters());

    $this->request = Request::createFromIlluminateRequest($illuminateRequest);

    $this->signatureHandler = new class($this->request) extends SignatureHandler {
        public $signature = '/commandName handlerName {argument} {--option} {--another-option}';

        public function handle(Request $request): Response
        {
            return true;
        }
    };
});

it('throws an exception if a signature has not been set', function () {
    new class($this->request) extends SignatureHandler {
        public function handle(Request $request): Response
        {
            return true;
        }
    };
})->throws(InvalidHandler::class);

it('cannot handle requests with a command that does not match the signature', function () {
    $signatureHandler = new class($this->request) extends SignatureHandler {
        public $signature = '/commandName another';

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    expect($signatureHandler->canHandle($this->request))->toBeFalse();

    $signatureHandler = new class($this->request) extends SignatureHandler {
        public $signature = '/another handlerName';

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    expect($signatureHandler->canHandle($this->request))->toBeFalse();
});

it('can handle requests with a valid signature', function () {
    expect($this->signatureHandler->canHandle($this->request))->toBeTrue();
});

it('can get the value of an argument', function () {
    expect($this->signatureHandler->getArgument('argument'))->toBe('my-argument');
});

it('can get all arguments', function () {
    expect($this->signatureHandler->getArguments())->toBe(['argument' => 'my-argument']);
});

it('can determine which options have been set', function () {
    expect($this->signatureHandler->getOption('option'))->toBeTrue();
    expect($this->signatureHandler->getOption('another-option'))->toBeFalse();
});

it('can get all options', function () {
    expect($this->signatureHandler->getOptions())->toBe([
        'option' => true,
        'another-option' => false,
    ]);
});
