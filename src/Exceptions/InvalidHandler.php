<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;
use Spatie\SlashCommand\Handlers\BaseHandler;

class InvalidHandler extends Exception
{
    public static function handlerDoesNotExist($handler)
    {
        return new static("There is no class named `{$handler}`.");
    }

    public static function handlerDoesNotExtendFromBaseHandler($handler)
    {
        $baseHandlerClass = BaseHandler::class;

        return new static("The handler `{$handler}` does not extend the base handler `{$baseHandlerClass}`.");
    }
}
