<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentAction;
use Spatie\SlashCommand\AttachmentField;

beforeEach(function () {
    $this->attachment = new Attachment();
});

it('can add a field', function () {
    $this->attachment->addField(['title' => 'value']);

    $attachment = $this->attachment->toArray()['fields'][0];

    expect($attachment['title'])->toEqual('title');
    expect($attachment['value'])->toEqual('value');
});

it('can add a multiple fields using an associative array', function () {
    $this->attachment->addFields([
        'title' => 'value',
        'title2' => 'value2',
    ]);

    $attachments = $this->attachment->toArray()['fields'];

    expect($attachments[0]['title'])->toEqual('title');
    expect($attachments[0]['value'])->toEqual('value');

    expect($attachments[1]['title'])->toEqual('title2');
    expect($attachments[1]['value'])->toEqual('value2');
});

it('can add an array of attachment fields', function () {
    $attachmentField = AttachmentField::create('title', 'value');

    $this->attachment->addFields([$attachmentField]);

    $attachments = $this->attachment->toArray()['fields'];

    expect($attachments[0]['title'])->toEqual('title');
    expect($attachments[0]['value'])->toEqual('value');
});

it('can add a action', function () {
    $this->attachment->addAction(['name' => 'button', 'text' => 'a button', 'type' => 'button']);

    $action = $this->attachment->toArray()['actions'][0];

    expect($action['name'])->toBe('button');
    expect($action['text'])->toBe('a button');
    expect($action['type'])->toBe('button');
});

it('can add multiple actions using an associative array', function () {
    $this->attachment->addActions([
        ['name' => 'button1', 'text' => 'button1', 'type' => 'button'],
        ['name' => 'button2', 'text' => 'button2', 'type' => 'button'],
    ]);

    $attachments = $this->attachment->toArray()['actions'];

    expect($attachments[0]['name'])->toEqual('button1');
    expect($attachments[0]['text'])->toEqual('button1');
    expect($attachments[0]['type'])->toEqual('button');

    expect($attachments[1]['name'])->toEqual('button2');
    expect($attachments[1]['text'])->toEqual('button2');
    expect($attachments[1]['type'])->toEqual('button');
});

it('can add an array of attachment actions', function () {
    $attachmentAction = AttachmentAction::create('action', 'click me', 'button');

    $this->attachment->addActions([$attachmentAction]);

    $actions = $this->attachment->toArray()['actions'];

    expect($actions[0]['name'])->toBe('action');
    expect($actions[0]['text'])->toBe('click me');
    expect($actions[0]['type'])->toBe('button');
});
