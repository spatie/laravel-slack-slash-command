<?php

namespace Spatie\SlashCommand;

use GuzzleHttp\Client;
use Illuminate\Http\Response as IlluminateResponse;

class Response
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    /** @var string */
    protected $text;

    /** @var string */
    protected $responseType = 'ephemeral';

    /** @var string */
    protected $channel;

    /** @var string */
    protected $attachments = '';

    /** @var \GuzzleHttp\Client */
    protected $client;

    public static function create(Request $request): Response
    {
        $client = app(Client::class);

        return (new static($client, $request))
            ->displayResponseToUserWhoTypedCommand();
    }

    public function __construct(Client $client, Request $request)
    {
        $this->client = $client;

        $this->request = $request;

        $this->channel = $request->channelName;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText(string $text)
    {
        $this->text = $text;

        return $this;
    }

    public function onChannel(string $channelName)
    {
        $this->channel = $channelName;

        return $this;
    }

    /**
     * @return $this
     */
    public function displayResponseToUserWhoTypedCommand()
    {
        $this->responseType = 'ephemeral';

        return $this;
    }

    /**
     * @return $this
     */
    public function displayResponseToEveryoneOnChannel()
    {
        $this->responseType = 'in_channel';

        return $this;
    }

    /**
     * Send the response to Slack.
     */
    public function send()
    {
        $this->client->post($this->request->responseUrl, ['json' => $this->getPayload()]);
    }

    public function getIlluminateResponse(): IlluminateResponse
    {
        return new IlluminateResponse($this->getPayload());
    }

    protected function getPayload(): array
    {
        return [
            'text' => $this->text,
            'channel' => $this->channel,
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => $this->responseType,
            'attachments' => $this->attachments,
        ];
    }
}
