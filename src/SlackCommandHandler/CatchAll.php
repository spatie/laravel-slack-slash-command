<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use Spatie\SlashCommand\SlashCommandResponse;

class CatchAll extends BaseHandler
{
    public function handleCurrentRequest(): SlashCommandResponse
    {
        /*
         * Example of how delayed responses can be sent
         *
        SlashCommandResponse::createForRequest($this->request)
            ->setText('yow yow delayed')
            ->send();
        */

        return $this->respond("This is a catch all response. You typed `{$this->getCommandText()}`");
    }

    public function canHandleCurrentRequest(): bool
    {
        return true;
    }
}
