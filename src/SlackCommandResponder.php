<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request;
use Spatie\SlashCommand\SlashCommandHandler\BaseHandler;

class SlackCommandResponder
{
    /** @var array */
    protected $commandConfig;
    
    /** @var \Illuminate\Http\Request */
    protected $request;

    public function __construct(array $commandConfig, Request $request)
    {
        $this->commandConfig = $commandConfig;

        $this->request = $request;
    }

    public function getResponse(): SlashCommandResponse {

        $this->guardAgainstInvalidRequest();

        $handler = $this->determineResponseHandler();

        $response = $handler->handleCurrentRequest();

        return $response->finalize();
    }

    protected function guardAgainstInvalidRequest()
    {
        if (!request()->has('token')) {
            throw InvalidSlashCommandRequest::tokenNotFound();
        }

        if (request()->get('token') != $this->commandConfig['verification_token']) {
            throw InvalidSlashCommandRequest::invalidToken(request()->get('token'));
        }
    }
    
    protected function determineResponseHandler(): BaseHandler
    {
        $handler = collect($this->commandConfig['handlers'])
            ->map(function (string $handlerClassName) {
                return new $handlerClassName(request());
            })
            ->filter(function (BaseHandler $handler) {
                return $handler->canHandleCurrentRequest();
            })->first();

        if (!$handler) {
            throw RequestCouldNotBeProcessed::noHandlerFound(request());
        }
        return $handler;
    }
}