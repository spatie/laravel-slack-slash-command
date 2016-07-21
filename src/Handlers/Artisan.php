<?php

namespace Spatie\SlashCommand\Handlers;

use Spatie\SlashCommand\Jobs\ArtisanJob;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class Artisan extends BaseHandler
{
    public function handle(Request $request): Response
    {
        $this->dispatch(new ArtisanJob());

        return $this->respondToSlack('Performing artisan command...');
    }

    public function canHandle(Request $request): bool
    {
        return starts_with($request->text, 'artisan');
    }
}
