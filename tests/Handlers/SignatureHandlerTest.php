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
    $this->expectException(InvalidHandler::class);

    new class($this->request) extends SignatureHandler {
        public function handle(Request $request): Response
        {
            return true;
        }
    };
});

it('cannot handle requests with a command that does not match the signature', function () {
    $signatureHandler = new class($this->request) extends SignatureHandler {
        public $signature = '/commandName another';

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    $this->assertFalse($signatureHandler->canHandle($this->request));

    $signatureHandler = new class($this->request) extends SignatureHandler {
        public $signature = '/another handlerName';

        public function handle(Request $request): Response
        {
            return true;
        }
    };

    $this->assertFalse($signatureHandler->canHandle($this->request));
});

it('can handle requests with a valid signature', function () {
    $this->assertTrue($this->signatureHandler->canHandle($this->request));
});

it('can get the value of an argument', function () {
    $this->assertSame('my-argument', $this->signatureHandler->getArgument('argument'));
});

it('can get all arguments', function () {
    $this->assertSame(['argument' => 'my-argument'], $this->signatureHandler->getArguments());
});

it('can determine which options have been set', function () {
    $this->assertTrue($this->signatureHandler->getOption('option'));
    $this->assertFalse($this->signatureHandler->getOption('another-option'));
});

it('can get all options', function () {
    $this->assertSame([
        'option' => true,
        'another-option' => false,
    ], $this->signatureHandler->getOptions());
});
