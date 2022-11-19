<?php

namespace Spatie\SlashCommand\Test\Handlers;

it('can catch a request', function () {
    $command = '/my command';
    $text = 'some text';

    $response = $this->call('POST', 'test-url', [
        'token' => 'test-token',
        'command' => $command,
        'text' => $text,
    ]);

    $response->assertSuccessful();
    $response->assertJsonFragment([
        'text' => "I did not recognize this command: `{$command} {$text}`",
    ]);
});
