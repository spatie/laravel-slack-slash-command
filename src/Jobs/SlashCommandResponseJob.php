<?php

namespace Spatie\SlashCommand\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

abstract class ResponseJob implements ShouldQueue, HandlesSlashCommand
{
    /** @var \Spatie\SlashCommand\Request */
    public $request;

    public function getResponse(): Response
    {
        return Response::create($this->request);
    }

    public function setResponse(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function respondToSlack(string $text): Response
    {
        return $this->getResponse()->setText($text);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    abstract public function handle();
}
