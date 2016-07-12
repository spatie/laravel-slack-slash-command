<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\SlashCommandData;
use Spatie\SlashCommand\SlashCommandResponse;
use Spatie\SlashCommand\SlashCommandResponseJob;

abstract class BaseHandler implements HandlesSlashCommand
{
    use DispatchesJobs;

    /** @var \Spatie\SlashCommand\SlashCommandRequest */
    protected $request;

    /** @var \Spatie\SlashCommand\SlashCommandData */
    protected $slashCommandData;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->slashCommandData = SlashCommandData::createForRequest($request);
    }

    public function respondToSlack(string $text): SlashCommandResponse
    {
        return SlashCommandResponse::create($this->slashCommandData)->setText($text);
    }

    protected function dispatch(SlashCommandResponseJob $job)
    {
        $job->slashCommandData = $this->slashCommandData;

        return app(Dispatcher::class)->dispatch($job);
    }

    public function getSlashCommandData(): SlashCommandData
    {
        return $this->getSlashCommandData();
    }

    abstract public function handleCurrentRequest(): SlashCommandResponse;

    abstract public function canHandleCurrentRequest(): bool;
}
