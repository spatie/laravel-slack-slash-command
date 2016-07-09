<?php

namespace Spatie\Slashcommand\SlashCommandHandler;

use Spatie\Slashcommand\SlashCommandRequest;
use Spatie\Slashcommand\SlashCommandResponse;

class CatchAll extends BaseHandler
{
    public function handle(SlashCommandRequest $slashCommandRequest): SlashCommandResponse
    {
        return $this->respond('This is a catch all reponse');
    }

    public function canHandle(SlashCommandRequest $slashCommandRequest): bool
    {
        return true;
    }
}
