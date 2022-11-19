<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\AttachmentAction;

it('can set a value', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setValue('value');

    $action = $attachmentAction->toArray();

    $this->assertSame('action', $action['name']);
    $this->assertSame('an action', $action['text']);
    $this->assertSame('button', $action['type']);
    $this->assertSame('value', $action['value']);
});

it('can set a style', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setStyle(AttachmentAction::STYLE_PRIMARY);

    $action = $attachmentAction->toArray();

    $this->assertSame('action', $action['name']);
    $this->assertSame('an action', $action['text']);
    $this->assertSame('button', $action['type']);
    $this->assertSame('primary', $action['style']);
});

it('can set a confirmation hash', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setConfirmation(['text' => 'are you sure you want to do that?']);

    $action = $attachmentAction->toArray();

    $this->assertSame('action', $action['name']);
    $this->assertSame('an action', $action['text']);
    $this->assertSame('button', $action['type']);
    $this->assertSame(['text' => 'are you sure you want to do that?'], $action['confirm']);
})->skip();
