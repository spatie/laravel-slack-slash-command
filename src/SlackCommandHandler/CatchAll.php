<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Spatie\SlashCommand\SlashCommandResponse;

class CatchAll extends BaseHandler
{
    public function handleCurrentRequest(): SlashCommandResponse
    {
        return $this
            ->respond("This is a catch all response. You typed `{$this->getCommandText()}`");
    }

    public function canHandleCurrentRequest(): bool
    {
        return true;
    }
}
