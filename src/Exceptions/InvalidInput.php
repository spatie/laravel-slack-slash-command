<?php

namespace Spatie\SlashCommand\Exceptions;

use Exception;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class InvalidInput extends SlackSlashCommandException
{
    protected $handler;

    public function __construct($message, SignatureHandler $handler, Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->handler = $handler;
    }

    public function getResponse(Request $request): Response
    {
        return parent::getResponse($request)
            ->withAttachment(
                Attachment::create()
                    ->setText($this->handler->getHelpDescription())
            );
    }
}
