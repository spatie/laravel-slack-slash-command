<?php

namespace Spatie\SlashCommand;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Response as IlluminateResponse;

class Response
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    /** @var string */
    protected $text;

    /** @var string */
    protected $responseType;

    /** @var string */
    protected $channel;

    /** @var string */
    protected $icon = '';

    /** @var \Illuminate\Support\Collection */
    protected $attachments;

    /** @var \GuzzleHttp\Client */
    protected $client;

    public static function create(Request $request): self
    {
        $client = app(Client::class);

        return new static($client, $request);
    }

    public function __construct(Client $client, Request $request)
    {
        $this->client = $client;

        $this->request = $request;

        $this->channel = $request->channelName;

        $this->displayResponseToUserWhoTypedCommand();

        $this->attachments = new \Illuminate\Support\Collection();
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function withText(string $text)
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
     * @param \Spatie\SlashCommand\Attachment $attachment
     *
     * @return $this
     */
    public function withAttachment(Attachment $attachment)
    {
        $this->attachments->push($attachment);

        return $this;
    }

    /**
     * @param array|\Spatie\SlashCommand\Attachment $attachments
     *
     * @return $this
     */
    public function withAttachments($attachments)
    {
        if (! is_array($attachments)) {
            $attachments = [$attachments];
        }

        foreach ($attachments as $attachment) {
            $this->withAttachment($attachment);
        }

        return $this;
    }

    /**
     * @param string $channelName
     *
     * @return $this
     */
    public function displayResponseToEveryoneOnChannel(string $channelName = '')
    {
        $this->responseType = 'in_channel';

        if ($channelName !== '') {
            $this->onChannel($channelName);
        }

        return $this;
    }

    /**
     * Set the icon (either URL or emoji) we will post as.
     *
     * @param string $icon
     *
     * @return $this
     */
    public function useIcon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIconType(): string
    {
        if (empty($this->icon)) {
            return '';
        }

        if (Str::startsWith($this->icon, ':') && Str::endsWith($this->icon, ':')) {
            return 'icon_emoji';
        }

        return 'icon_url';
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
        $payload = [
            'text' => $this->text,
            'channel' => $this->channel,
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => $this->responseType,
            'attachments' => $this->attachments->map(function (Attachment $attachment) {
                return $attachment->toArray();
            })->toArray(),
        ];

        if (! empty($this->icon)) {
            $payload[$this->getIconType()] = $this->icon;
        }

        return $payload;
    }
}
