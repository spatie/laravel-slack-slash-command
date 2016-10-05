<?php

namespace Spatie\SlashCommand;

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Routing\Controller as IlluminateController;
use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidRequest;
use Spatie\SlashCommand\Exceptions\SlackSlashCommandException;
use Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled;

class Controller extends IlluminateController
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    /** @var \Illuminate\Support\Collection */
    protected $config;

    public function __construct(IlluminateRequest $request, Repository $config)
    {
        $this->request = Request::createFromIlluminateRequest($request);

        $this->config = collect($config->get('laravel-slack-slash-command'));
    }

    public function getResponse(): IlluminateResponse
    {
        $this->guardAgainstInvalidRequest();

        $handler = $this->determineHandler();

        try {
            $response = $handler->handle($this->request);
        } catch (SlackSlashCommandException $exception) {
            $response = $exception->getResponse($this->request);
        } catch (Exception $exception) {
            $response = $this->convertToResponse($exception);
        }

        return $response->getIlluminateResponse();
    }

    protected function guardAgainstInvalidRequest()
    {
        if (! request()->has('token')) {
            throw InvalidRequest::tokenNotFound();
        }

        if ($this->request->get('token') != $this->config->get('token')) {
            throw InvalidRequest::invalidToken($this->request->get('token'));
        }
    }

    /**
     * @return \Spatie\SlashCommand\Handlers\BaseHandler
     *
     * @throws \Spatie\SlashCommand\Exceptions\RequestCouldNotBeHandled
     */
    protected function determineHandler()
    {
        $handler = collect($this->config->get('handlers'))
            ->map(function (string $handlerClassName) {
                if (! class_exists($handlerClassName)) {
                    throw InvalidHandler::handlerDoesNotExist($handlerClassName);
                }

                return new $handlerClassName($this->request);
            })
            ->filter(function (HandlesSlashCommand $handler) {
                return $handler->canHandle($this->request);
            })
            ->first();

        if (! $handler) {
            throw RequestCouldNotBeHandled::noHandlerFound($this->request);
        }

        return $handler;
    }

    protected function convertToResponse(Exception $exception) : Response
    {
        $message = config('app.debug') ? (string) $exception : 'Whoops, something went wrong...';

        $exception = new SlackSlashCommandException(
            $message,
            $exception->getCode(),
            $exception
        );

        $response = $exception->getResponse($this->request);

        return $response;
    }
}
