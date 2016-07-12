<?php

namespace Spatie\SlashCommand;

use GuzzleHttp\Client;
use Illuminate\Http\Response;

class SlashCommandResponse
{
    /** @var \Spatie\SlashCommand\SlashCommandData */
    protected $slashCommandData;

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

    public static function create(SlashCommandData $slashCommandData): SlashCommandResponse
    {
        $client = app(Client::class);

        return (new static($client, $slashCommandData))
            ->displayResponseOnlyToUserWhoTypedCommand();
    }

    public function __construct(Client $client, SlashCommandData $slashCommandData)
    {
        $this->client = $client;

        $this->slashCommandData = $slashCommandData;

        $this->channel = $slashCommandData->channelName;
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
    public function displayResponseOnlyToUserWhoTypedCommand()
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
        $this->client->post($this->slashCommandData->responseUrl, ['json' => $this->getPayload()]);
    }

    /*
     * Get the http response
     */
    public function getHttpResponse(): Response
    {
        return new Response($this->getPayload());
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
