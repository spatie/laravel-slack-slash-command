<?php

namespace Spatie\LaravelSlack\SlashCommandHandler;

use Illuminate\Support\Collection as IlluminateCollection;
use Spatie\LaravelSlack\InvalidSlashCommandHandler;
use Spatie\LaravelSlack\SlashCommandRequest;
use Spatie\LaravelSlack\SlashCommandResponse;

class Collection extends IlluminateCollection
{
    /** @var  \Spatie\LaravelSlack\SlashCommandRequest */
    protected $request;
    
    public static function createForClasses(array $commandHandlers)
    {
        return new static($commandHandlers);
    }

    public function __construct(array $commandHandlers, SlashCommandRequest $request)
    {
        $this->request = $request;
        
        $commandHandlers = collect($commandHandlers)
            ->each(function ($className) {
                if (!class_exists($className)) {
                    throw InvalidSlashCommandHandler::handlerDoesNotExist($className);
                }

                if (!$className instanceof BaseHandler) {
                    throw InvalidSlashCommandHandler::handlerDoesNotExendFromBaseHandler($className);
                }
            })
            ->map(function (string $className) {
                return new $className($this->request);
            })
            ->toArray();

        parent::__construct($commandHandlers);
    }

    public function getResponse(): SlashCommandResponse
    {
        $handler = $this->items
            ->first(function (BaseHandler $commandHandler) {
                return $commandHandler->canHandle($this->request);
            });

        if (! $handler) {
            throw RequestCouldNotBeProcessed::noHandlerFound($this->request);
        }

        $response = $handler->handle($slashCommandRequest);

        return $response;

    }
}