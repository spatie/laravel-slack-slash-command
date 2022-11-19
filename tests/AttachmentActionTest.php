<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\AttachmentAction;

it('can set a value', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setValue('value');

    $action = $attachmentAction->toArray();

    expect($action['name'])->toBe('action');
    expect($action['text'])->toBe('an action');
    expect($action['type'])->toBe('button');
    expect($action['value'])->toBe('value');
});

it('can set a style', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setStyle(AttachmentAction::STYLE_PRIMARY);

    $action = $attachmentAction->toArray();

    expect($action['name'])->toBe('action');
    expect($action['text'])->toBe('an action');
    expect($action['type'])->toBe('button');
    expect($action['style'])->toBe('primary');
});

it('can set a confirmation hash', function () {
    $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                        ->setConfirmation(['text' => 'are you sure you want to do that?']);

    $action = $attachmentAction->toArray();

    expect($action['name'])->toBe('action');
    expect($action['text'])->toBe('an action');
    expect($action['type'])->toBe('button');
    expect($action['confirm'])->toBe(['text' => 'are you sure you want to do that?']);
})->skip();
