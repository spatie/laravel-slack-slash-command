<?php

namespace Spatie\LaravelSlack\SlashCommandHandler;

use Spatie\LaravelSlack\SlashCommandRequest;
use Spatie\LaravelSlack\SlashCommandResponse;

class CatchAll extends BaseHandler
{

    public function handle(SlashCommandRequest $slashCommandRequest): SlashCommandResponse
    {
        return 'handle';
    }

    public function canHandle(SlashCommandRequest $slashCommandRequest): bool
    {
        return true;
    }
}