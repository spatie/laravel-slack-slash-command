<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;

class InvalidSignature extends Exception
{
    public static function signatureMustContainASpace($signature)
    {
        return new static("A signature must contain at least one space. None found in `$signature`.");
    }
}