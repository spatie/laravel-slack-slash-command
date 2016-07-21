<?php

namespace Spatie\SlashCommand\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidSignature;

class SignatureParts
{
    /** @var string */
    protected $signature;

    public function __construct(string $signature)
    {
        $this->signature = $signature;

        if (!str_contains($this->signature, ' ')) {
            throw InvalidSignature::signatureMustContainASpace($this->signature);
        }
    }

    public function getSlashCommandName(): string
    {
        return explode(' ', $this->signature)[0];
    }

    public function getHandlerName(): string
    {
        return explode(' ', $this->signature)[1];
    }

    public function getSignatureWithoutCommandName(): string
    {
        return explode(' ', $this->signature, 2)[1];
    }
}