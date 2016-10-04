<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class SlackSlashCommandException extends Exception
{
    public function getResponse(Request $request): Response
    {
        return Response::create($request)
            ->withAttachment(Attachment::create()
                ->setColor('danger')
                ->setText($this->getMessage())
                ->setFallback($this->getMessage())
            );
    }
}
