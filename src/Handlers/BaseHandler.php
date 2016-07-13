<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Jobs\ResponseJob;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

abstract class BaseHandler implements HandlesSlashCommand
{
    use DispatchesJobs;

    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function respondToSlack(string $text): Response
    {
        return Response::create($this->request)->setText($text);
    }

    protected function dispatch(ResponseJob $job)
    {
        $job->setResponse($this->request);

        return app(Dispatcher::class)->dispatch($job);
    }

    public function getRequest(): Request
    {
        return $this->getRequest();
    }

    abstract public function handle(Request $request): Response;

    abstract public function canHandle(Request $request): bool;
}
