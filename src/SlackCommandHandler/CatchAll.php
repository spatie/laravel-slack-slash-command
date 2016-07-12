<?php

namespace Spatie\SlashCommand\SlashCommandHandler;

use App\Jobs\TestJob;
use Spatie\SlashCommand\SlashCommandResponse;

class CatchAll extends BaseHandler
{
    public function handleCurrentRequest(): SlashCommandResponse
    {
        //$this->dispatch(new TestJob());

        return $this->respondToSlack("Received this message `{$this->slashCommandData->text}`");
    }

    public function canHandleCurrentRequest(): bool
    {
        return true;
    }
}
