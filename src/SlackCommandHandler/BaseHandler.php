<?php

namespace Spatie\LaravelSlack\SlashCommandHandler;

use Spatie\LaravelSlack\SlashCommandRequest;
use Spatie\LaravelSlack\SlashCommandResponse;

abstract class BaseHandler
{
    /** @var \Spatie\LaravelSlack\SlashCommandRequest */
    protected $request;

    public function __construct(SlashCommandRequest $request)
    {
        $this->request = $request;
    }

    abstract public function handle(SlashCommandRequest $slashCommandRequest): SlashCommandResponse;

    abstract public function canHandle(SlashCommandRequest $slashCommandRequest): bool;

    public function respond(string $text)
    {
        
    }
}