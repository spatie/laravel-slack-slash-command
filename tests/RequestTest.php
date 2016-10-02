<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Request;

class RequestTest extends TestCase
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters());

        $this->request = Request::createFromIlluminateRequest($illuminateRequest);
    }

    /** @test */
    public function it_can_get_the_parameters_as_public_properties()
    {
        $this->assertSame($this->getPostParameters()['token'], $this->request->token);
    }

    /** @test */
    public function it_provides_a_get_function_to_retrieve_parameters()
    {
        $this->assertSame($this->getPostParameters()['token'], $this->request->get('token'));
    }

    /** @test */
    public function it_return_an_empty_string_when_getting_a_non_existing_property()
    {
        $this->assertSame('', $this->request->get('does not exist'));
    }

    /** @test */
    public function it_can_get_all_parameters_at_once()
    {
        $this->assertSame($this->getPostParameters()['token'], $this->request->all()['token']);
    }

    protected function getPostParameters(): array
    {
        return [
            'token' => 'test-token',
            'team_id' => 'T123',
            'team_domain' => 'Company',
            'channel_id' => 'C123',
            'channel_name' => 'General',
            'user_id' => 'U123',
            'user_name' => 'Bob',
            'command' => 'my-command',
            'text' => 'this is the text',
            'response_url' => 'https://slack.com/respond',
        ];
    }
}
