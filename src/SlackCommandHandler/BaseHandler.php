<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Illuminate\Http\Request;
use Spatie\SlashCommand\SlashCommandResponse;

abstract class BaseHandler
{
    /** @var \Spatie\SlashCommand\SlashCommandRequest */
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    abstract public function handleCurrentRequest(): SlashCommandResponse;

    abstract public function canHandleCurrentRequest(): bool;

    public function getCommandText()
    {
        return $this->request->get('text');
    }

    public function respond(string $text): SlashCommandResponse
    {
        return SlashCommandResponse::createForRequest($this->request)->setText($text);
    }
}
