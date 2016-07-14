<?php

namespace Spatie\SlashCommand\Handlers;

use App\Jobs\TestJob;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class CatchAll extends BaseHandler
{
    public function handle(Request $request): Response
    {
        //$this->dispatch(new TestJob());

        return $this->respondToSlack("I do not recognize this command: `{$request->command} {$request->text}`");
    }

    public function canHandle(Request $request): bool
    {
        return true;
    }
}
