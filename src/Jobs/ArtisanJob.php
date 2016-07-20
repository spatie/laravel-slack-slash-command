<?php

namespace Spatie\SlashCommand\Jobs;

use Artisan;
use Spatie\SlashCommand\Attachment;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ArtisanJob extends SlashCommandResponseJob
{
    public function handle()
    {
        $artisanCommand = substr($this->request->text, 8);

        try {
            Artisan::call($artisanCommand, []);

            $this->respondToSlack(Artisan::output())->send();
        } catch (CommandNotFoundException $exception) {
            $this->respondToSlack('Whoops... something went wrong!')
                ->withAttachment(Attachment::create()
                    ->setColor('danger')
                    ->setText("The Artisan command `{$artisanCommand}` does not exist.")
                )
                ->send();
        }
    }
}
