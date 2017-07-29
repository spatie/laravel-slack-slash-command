<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Test\TestCase;
use Spatie\SlashCommand\Handlers\SignatureParts;
use Spatie\SlashCommand\Exceptions\InvalidSignature;

class SignaturePartsTest extends TestCase
{
    /** @var \Spatie\SlashCommand\Handlers\SignatureParts */
    protected $signatureParts;

    public function setUp()
    {
        $this->signatureParts = new SignatureParts('/commandName handlerName {argument} {--option}');

        parent::setUp();
    }

    /** @test */
    public function it_can_determine_the_commandName()
    {
        $this->assertSame('commandName', $this->signatureParts->getSlashCommandName());
    }

    /** @test */
    public function it_can_determine_the_handlerName()
    {
        $this->assertSame('handlerName', $this->signatureParts->getHandlerName());
    }

    /** @test */
    public function it_can_determine_the_signature_without_the_commandName()
    {
        $this->assertSame('handlerName {argument} {--option}', $this->signatureParts->getSignatureWithoutCommandName());
    }

    /** @test */
    public function it_can_determine_the_arguments_and_options()
    {
        $this->assertSame('{argument} {--option}', $this->signatureParts->getArgumentsAndOptions());
    }

    /** @test */
    public function it_will_throw_an_exception_if_a_signature_does_not_contain_a_space()
    {
        $this->expectException(InvalidSignature::class);

        new SignatureParts('commandName');
    }
}
