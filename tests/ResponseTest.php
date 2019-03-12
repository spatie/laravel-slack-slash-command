<?php

namespace Spatie\SlashCommand\Test;

use Mockery;
use GuzzleHttp\Client;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class ResponseTest extends TestCase
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    /** @var \Spatie\SlashCommand\Response */
    protected $response;

    /** @var string */
    protected $responseUrl;

    public function setUp(): void
    {
        parent::setUp();

        $this->responseUrl = 'https://slack.com/respond';

        $illuminateRequest = $this->getIlluminateRequest([
            'token' => 'test-token',
            'team_id' => 'T123',
            'team_domain' => 'Company',
            'channel_id' => 'C123',
            'channel_name' => 'General',
            'user_id' => 'U123',
            'user_name' => 'Bob',
            'command' => 'my-command',
            'text' => 'this is the text',
            'response_url' => $this->responseUrl,
        ]);

        $this->request = Request::createFromIlluminateRequest($illuminateRequest);

        $this->client = Mockery::spy(Client::class);

        $this->response = new Response($this->client, $this->request);
    }

    /** @test */
    public function it_provides_a_factory_method()
    {
        $response = Response::create($this->request);

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_can_send_a_text()
    {
        $this->response
            ->withText('hello')
            ->send();

        $expectedPayload = $this->getPayload(['text' => 'hello']);

        $this->client
            ->shouldHaveReceived('post')
            ->with($this->responseUrl, ['json' => $expectedPayload]);
    }

    /** @test */
    public function it_can_send_a_response_to_a_specific_channel()
    {
        $this->response
            ->withText('hello')
            ->onChannel('myChannel')
            ->send();

        $expectedPayload = $this->getPayload([
            'text' => 'hello',
            'channel' => 'myChannel',
        ]);

        $this->client
            ->shouldHaveReceived('post')
            ->with($this->responseUrl, ['json' => $expectedPayload]);
    }

    /** @test */
    public function it_can_send_a_response_to_all_members_on_a_channel()
    {
        $this->response
            ->withText('hello')
            ->displayResponseToEveryoneOnChannel('yetAnotherChannel')
            ->send();

        $expectedPayload = $this->getPayload([
            'text' => 'hello',
            'channel' => 'yetAnotherChannel',
            'response_type' => 'in_channel',
        ]);

        $this->client
            ->shouldHaveReceived('post')
            ->with($this->responseUrl, ['json' => $expectedPayload]);
    }

    public function getPayload(array $mergeVariables = []): array
    {
        return array_merge([
            'text' => '',
            'channel' => 'General',
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'response_type' => 'ephemeral',
            'attachments' => [],
        ], $mergeVariables);
    }
}
