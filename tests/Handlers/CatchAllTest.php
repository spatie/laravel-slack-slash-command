<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Illuminate\Http\Response;
use Spatie\SlashCommand\Test\TestCase;

class CatchAllTest extends TestCase
{
    /** @test */
    public function it_can_catch_a_request()
    {
        $command = '/my command';
        $text = 'some text';

        $response = $this->call('POST', 'test-url', [
            'token' => 'test-token',
            'command' => $command,
            'text' => $text,
        ]);

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame(200, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);

        $this->assertSame("I did not recognize this command: `{$command} {$text}`", $responseContent['text']);
    }
}
