<?php

namespace Spatie\SlashCommand\Exceptions;

use Spatie\SlashCommand\AttachmentField;

class FieldCannotBeAdded extends SlackSlashCommandException
{
    public static function invalidType()
    {
        return new static('You must pass either an array or an instance of '.AttachmentField::class);
    }
}
