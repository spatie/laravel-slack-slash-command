<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Spatie\SlashCommand\SlashCommandResponse;

class CatchAll extends BaseHandler
{
    public function handleCurrentRequest(): SlashCommandResponse
    {
        return $this->respondToSlack("Received this message `{$this->slashCommandData->text}`");
    }

    public function canHandleCurrentRequest(): bool
    {
        return true;
    }
}
