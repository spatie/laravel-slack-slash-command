<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Spatie\SlashCommand\SlashCommandRequest;
use Spatie\SlashCommand\SlashCommandResponse;

abstract class BaseHandler
{
    /** @var \Spatie\SlashCommand\SlashCommandRequest */
    protected $request;

    public function __construct(SlashCommandRequest $request)
    {
        $this->request = $request;
    }

    abstract public function handle(SlashCommandRequest $slashCommandRequest): SlashCommandResponse;

    abstract public function canHandle(SlashCommandRequest $slashCommandRequest): bool;

    public function respond(string $text): SlashCommandResponse
    {
        return SlashCommandResponse::createForRequest($this->request)->setText($text);
    }
}
