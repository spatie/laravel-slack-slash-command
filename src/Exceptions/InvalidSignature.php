<?php

namespace Spatie\SlashCommand\Exceptions;

class InvalidSignature extends SlackSlashCommandException
{
    public static function signatureMustContainASpace($signature)
    {
        return new static("A signature must contain at least one space. None found in `$signature`.");
    }
}
