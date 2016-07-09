<?php

namespace Spatie\Slashcommand\SlashCommandHandler;

use Spatie\Slashcommand\SlashCommandRequest;
use Spatie\Slashcommand\SlashCommandResponse;

abstract class BaseHandler
{
    /** @var \Spatie\Slashcommand\SlashCommandRequest */
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
