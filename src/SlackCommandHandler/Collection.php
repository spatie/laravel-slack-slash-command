<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Illuminate\Support\Collection as IlluminateCollection;
use Spatie\SlashCommand\InvalidSlashCommandHandler;
use Spatie\SlashCommand\SlashCommandRequest;
use Spatie\SlashCommand\SlashCommandResponse;

class Collection extends IlluminateCollection
{
    /** @var  \Spatie\SlashCommand\SlashCommandRequest */
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
