<?php

namespace Spatie\SlashCommand;

use Spatie\SlashCommand\SlashCommandHandler\BaseHandler;

class InvalidSlashCommandHandler extends \Exception
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
