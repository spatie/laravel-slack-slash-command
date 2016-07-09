<?php

namespace Spatie\SlashCommand;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
    
    /** @var \GuzzleHttp\Client  */
    protected  $client;

    public static function createForRequest(Request $request)
    {
        return app(SlashCommandResponse::class)
            ->setRequest($request)
            ->displayResponseOnlyToUserWhoTypedCommand();
    }

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->responseType = $request;

        return $this;
    }

    public function getResponseUrl()
    {
        return $this->request->get('response_url');
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text)
    {
        $this->text = $text;

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
     * Send the response to Slack
     */
    public function send()
    {
        $this->client->post($this->getResponseUrl(), ['json' => $this->getPayload()]);
    }

    /**
     * @return $this
     */
    public function finalize()
    {
        $this->headers->set('Content-Type', 'application/json');

        $this->setContent(json_encode($this->getPayload()));

        return $this;
    }

    protected function getPayload(): array
    {
        return [
            'text' => $this->text,
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => $this->responseType,
            'attachments' => $this->attachments,
        ];
    }
}
