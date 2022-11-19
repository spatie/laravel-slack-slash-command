<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidSignature;
use Spatie\SlashCommand\Handlers\SignatureParts;

beforeEach(function () {
    $this->signatureParts = new SignatureParts('/commandName handlerName {argument} {--option}');
});

it('can determine the commandName', function () {
    expect($this->signatureParts->getSlashCommandName())->toBe('commandName');
});

it('can determine the handlerName', function () {
    expect($this->signatureParts->getHandlerName())->toBe('handlerName');
});

it('can determine the signature without the commandName', function () {
    expect($this->signatureParts->getSignatureWithoutCommandName())->toBe('handlerName {argument} {--option}');
});

it('can determine the arguments and options', function () {
    expect($this->signatureParts->getArgumentsAndOptions())->toBe('{argument} {--option}');
});

it('will throw an exception if a signature does not contain a space', function () {
    new SignatureParts('commandName');
})->throws(InvalidSignature::class);
