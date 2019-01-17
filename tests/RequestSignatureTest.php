<?php

namespace Spatie\SlashCommand\Test;

use Illuminate\Http\Request;
use Spatie\SlashCommand\RequestSignature;

class RequestSignatureTest extends TestCase
{
    /** @var RequestSignature */
    protected $requestSignature;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test-signing');

        $this->requestSignature = new RequestSignature();
    }

    /** @test */
    public function it_can_create_new_signature()
    {
        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters(), $this->getHeaders());

        $signature = $this->requestSignature->create($illuminateRequest);

        $this->assertSame($this->getTestSignature(), $signature);
    }

    /** @test */
    public function it_cannot_create_new_signature_with_invalid_timestamp()
    {
        $headers = [
            'X-Slack-Request-Timestamp' => 1111,
        ];

        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters(), $headers);

        $signature = $this->requestSignature->create($illuminateRequest);

        $this->assertNotSame($this->getTestSignature(), $signature);
    }

    /** @test */
    public function it_cannot_create_new_signature_with_invalid_signing_secret()
    {
        $this->app['config']->set('laravel-slack-slash-command.signing_secret', 'test1-signing');

        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters(), $this->getHeaders());

        $signature = $this->requestSignature->create($illuminateRequest);

        $this->assertNotSame($this->getTestSignature(), $signature);
    }

    /** @test */
    public function it_cannot_create_new_signature_with_invalid_post_parameters()
    {
        $illuminateRequest = $this->getIlluminateRequest([], $this->getHeaders());

        $signature = $this->requestSignature->create($illuminateRequest);

        $this->assertNotSame($this->getTestSignature(), $signature);
    }

    protected function getPostParameters(): array
    {
        return [
            'token' => 'test-token',
            'user_id' => 'U123',
        ];
    }

    protected function getHeaders(): array
    {
        return [
            'X-Slack-Request-Timestamp' => 1234,
        ];
    }

    public function getIlluminateRequest($values, $headers = []): Request
    {
        $illuminateRequest = new Request($values);

        $illuminateRequest->headers->add($headers);

        return $illuminateRequest;
    }
}
