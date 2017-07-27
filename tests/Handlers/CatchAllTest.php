<?php

namespace Spatie\SlashCommand\Test\Handlers;

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

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'text' => "I did not recognize this command: `{$command} {$text}`"
        ]);
    }
}
