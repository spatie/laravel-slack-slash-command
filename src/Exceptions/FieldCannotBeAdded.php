<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;
use Spatie\SlashCommand\AttachmentField;

class FieldCannotBeAdded extends Exception
{
    public static function invalidType()
    {
        return new static('You must pass either an array or an instance of '.AttachmentField::class);
    }
}
