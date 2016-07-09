<?php

namespace Spatie\Slashcommand;

use Spatie\Slashcommand\SlashCommandHandler\BaseHandler;

class RequestCouldNotBeHandled extends \Exception
{
    public static function handlerDoesNotExist($handler)
    {
        return new static("There is no class named `{$handler}`.");
    }

    public static function handlerDoesNotExendFromBaseHandler($handler)
    {
        $baseHandlerClass = BaseHandler::class;

        return new static("The handler `{$handler}` does not extend the base handler `{$baseHandlerClass}`.");
    }
}
