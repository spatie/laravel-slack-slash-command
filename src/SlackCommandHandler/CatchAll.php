<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Spatie\SlashCommand\SlashCommandRequest;
use Spatie\SlashCommand\SlashCommandResponse;

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
