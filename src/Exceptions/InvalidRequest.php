<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;

class InvalidRequest extends Exception
{
    public static function tokenNotFound()
    {
        return new static('The request did not contain a token.');
    }

    public static function invalidToken($token)
    {
        return new static("The request had an invalid token `{$token}`.");
    }
}
