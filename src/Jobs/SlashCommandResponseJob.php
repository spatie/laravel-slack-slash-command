<?php

namespace Spatie\SlashCommand\Jobs;

use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\SlashCommand\HandlesSlashCommand;

abstract class SlashCommandResponseJob implements ShouldQueue, HandlesSlashCommand
{
    /** @var \Spatie\SlashCommand\Request */
    public $request;

    public function getResponse(): Response
    {
        return Response::create($this->request);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function respondToSlack(string $text = ''): Response
    {
        return $this->getResponse()->withText($text);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
