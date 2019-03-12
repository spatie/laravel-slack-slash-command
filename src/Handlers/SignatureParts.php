<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Support\Str;
use Spatie\SlashCommand\Exceptions\InvalidSignature;

class SignatureParts
{
    /** @var string */
    protected $signature;

    public function __construct(string $signature)
    {
        $this->signature = $signature;

        if (! Str::contains($this->signature, ' ')) {
            throw InvalidSignature::signatureMustContainASpace($this->signature);
        }
    }

    public function getSlashCommandName(): string
    {
        return ltrim(explode(' ', $this->signature)[0], '/');
    }

    public function getHandlerName(): string
    {
        return explode(' ', $this->signature)[1];
    }

    public function getSignatureWithoutCommandName(): string
    {
        return explode(' ', $this->signature, 2)[1];
    }

    public function getArgumentsAndOptions(): string
    {
        return explode(' ', $this->signature, 3)[2];
    }
}
