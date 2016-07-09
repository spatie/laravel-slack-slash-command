<?php

namespace Spatie\SlashCommand;


use Illuminate\Http\Request;
use Spatie\SlashCommand\SlashCommandHandler\BaseHandler;

class SlackCommandResponder
{
    /**
     * @var array
     */
    private $commandConfig;
    /**
     * @var Request
     */
    private $request;

    public function __construct(array $commandConfig, Request $request)
    {
        $this->commandConfig = $commandConfig;

        $this->request = $request;
    }

    public function getResponse() {

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

    /**
     * @return mixed
     */
    protected function determineResponseHandler()
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