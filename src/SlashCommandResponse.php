<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request;
use \Symfony\Component\HttpFoundation\Response;

class SlashCommandResponse extends Response
{
    /** @var SlashCommandRequest */
    protected $request;

    /** @var string */
    protected $text;

    /** @var string */
    protected $responseType = 'ephemeral';

    /** @var string */
    protected $attachments = '';

    public static function createForRequest(Request $request)
    {
        return (new static())
            ->setRequest($request)
            ->displayResponseOnlyToUserWhoTypedCommand();
    }

    public function setRequest(Request $request)
    {
        $this->responseType = $request;

        return $this;
    }

    public function setText(string $text)
    {
        $this->text = $text;

        return $this;
    }

    public function displayResponseOnlyToUserWhoTypedCommand()
    {
        $this->responseType = 'ephemeral';

        return $this;
    }

    public function displayResponseToEveryoneOnChannel()
    {
        $this->responseType = 'in_channel';

        return $this;
    }

    /**
     * Prepares the payload to be sent to the response.
     */
    public function finalize()
    {
        $payload = [
            'text' => $this->text,
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => $this->responseType,
        ];

        $payload['attachments'] = $this->attachments;

        $this->headers->set('Content-Type', 'application/json');

        $this->setContent(json_encode($payload));

        return $this;
    }
}
