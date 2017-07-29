<?php

namespace Spatie\SlashCommand\Exceptions;

use Spatie\SlashCommand\AttachmentAction;

class ActionCannotBeAdded extends SlackSlashCommandException
{
    public static function invalidType()
    {
        return new static('You must pass either an array or an instance of '.AttachmentAction::class);
    }
}
