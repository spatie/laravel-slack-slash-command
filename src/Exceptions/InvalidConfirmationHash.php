<?php

namespace Spatie\SlashCommand\Exceptions;

class InvalidConfirmationHash extends SlackSlashCommandException
{
    public static function missingTextField()
    {
        return new self('The confirmation hash is missing the text field.');
    }
}
