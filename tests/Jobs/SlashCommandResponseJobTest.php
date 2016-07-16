<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Jobs\SlashCommandResponseJob;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Test\TestCase;

class SlashCommandResponseJobTest extends TestCase
{
    /** @var \Spatie\SlashCommand\Request  */
    protected $request;

    /** @var \Spatie\SlashCommand\Jobs\SlashCommandResponseJob */
    protected $job;

    public function setUp()
    {
        parent::setUp();

        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters());

        $this->request = Request::createFromIlluminateRequest($illuminateRequest);

        $this->job = new class extends SlashCommandResponseJob
        {
            public function handle()
            {

            }
        };

        $this->job->setRequest($this->request);
    }

    /** @test */
    public function it_can_get_a_request()
    {
        $this->assertInstanceOf(Request::class, $this->request);
        $this->assertInstanceOf(Request::class, $this->job->getRequest());
    }

    /** @test */
    public function it_can_get_a_response()
    {
        $this->assertInstanceOf(Response::class, $this->job->getResponse());
    }

    protected function getPostParameters(array $mergeVariables = []): array
    {
        return array_merge([
            'token' => 'test-token',
            'team_id' => 'T123',
            'team_domain' => 'Company',
            'channel_id' => 'C123',
            'channel_name' => 'General',
            'user_id' => 'U123',
            'user_name' => 'Bob',
            'command' => 'command',
            'text' => 'my-argument --option',
            'response_url' => 'https://slack.com/respond',
        ], $mergeVariables);
    }
}
