<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Response;

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

    public static function createForRequest(SlashCommandRequest $request)
    {
        return (new static())
            ->setRequest($request)
            ->displayResponseOnlyToUserWhoTypedCommand();
    }

    public function setRequest(SlashCommandRequest $request)
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
        $this->type = 'in_channel';

        return $this;
    }

    /**
     * Prepares the payload to be sent to the reponse.
     */
    public function __toString()
    {
        $payload = [
            'text' => $this->getText(),
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => $this->responseType,
        ];

        $payload['attachments'] = $this->attachments;

        $this->setContent($payload);

        return parent::__toString();
    }
}
