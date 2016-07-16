<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Jobs\SlashCommandResponseJob;
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
        return Response::create($this->request)->withText($text);
    }

    protected function dispatch(SlashCommandResponseJob $job)
    {
        $job->setRequest($this->request);

        return app(Dispatcher::class)->dispatch($job);
    }

    public function getRequest(): Request
    {
        return $this->getRequest();
    }

    abstract public function handle(Request $request): Response;
}
