<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidSignature;
use Spatie\SlashCommand\Handlers\SignatureParts;

beforeEach(function () {
    $this->signatureParts = new SignatureParts('/commandName handlerName {argument} {--option}');
});

it('can determine the commandName', function () {
    $this->assertSame('commandName', $this->signatureParts->getSlashCommandName());
});

it('can determine the handlerName', function () {
    $this->assertSame('handlerName', $this->signatureParts->getHandlerName());
});

it('can determine the signature without the commandName', function () {
    $this->assertSame('handlerName {argument} {--option}', $this->signatureParts->getSignatureWithoutCommandName());
});

it('can determine the arguments and options', function () {
    $this->assertSame('{argument} {--option}', $this->signatureParts->getArgumentsAndOptions());
});

it('will throw an exception if a signature does not contain a space', function () {
    $this->expectException(InvalidSignature::class);

    new SignatureParts('commandName');
});
