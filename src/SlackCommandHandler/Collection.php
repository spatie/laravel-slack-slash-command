<?php

namespace Spatie\Slashcommand\SlashCommandHandler;

use Illuminate\Support\Collection as IlluminateCollection;
use Spatie\Slashcommand\InvalidSlashCommandHandler;
use Spatie\Slashcommand\SlashCommandRequest;
use Spatie\Slashcommand\SlashCommandResponse;

class Collection extends IlluminateCollection
{
    /** @var  \Spatie\Slashcommand\SlashCommandRequest */
    protected $request;

    public static function createForClasses(array $commandHandlers)
    {
        return new static($commandHandlers);
    }

    public function __construct(array $commandHandlers, SlashCommandRequest $request)
    {
        $this->request = $request;

        $commandHandlers = collect($commandHandlers)
            ->each(function (string $className) {
                $this->guardAgainstInvalidHandlerClassName($className);
            })
            ->map(function (string $className) {
                return new $className($this->request);
            })
            ->toArray();

        parent::__construct($commandHandlers);
    }

    protected function guardAgainstInvalidHandlerClassName(string $className)
    {
        if (!class_exists($className)) {
            throw InvalidSlashCommandHandler::handlerDoesNotExist($className);
        }

        if (!$className instanceof BaseHandler) {
            throw InvalidSlashCommandHandler::handlerDoesNotExendFromBaseHandler($className);
        }
    }

    public function getResponse(): SlashCommandResponse
    {
        $handler = $this->items
            ->first(function (BaseHandler $commandHandler) {
                return $commandHandler->canHandle($this->request);
            });

        if (!$handler) {
            throw RequestCouldNotBeProcessed::noHandlerFound($this->request);
        }

        $response = $handler->handle($this->slashCommandRequest);

        return $response;
    }
}
