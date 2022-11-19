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

    $this->assertEquals('title', $attachment['title']);
    $this->assertEquals('value', $attachment['value']);
});

it('can add a multiple fields using an associative array', function () {
    $this->attachment->addFields([
        'title' => 'value',
        'title2' => 'value2',
    ]);

    $attachments = $this->attachment->toArray()['fields'];

    $this->assertEquals('title', $attachments[0]['title']);
    $this->assertEquals('value', $attachments[0]['value']);

    $this->assertEquals('title2', $attachments[1]['title']);
    $this->assertEquals('value2', $attachments[1]['value']);
});

it('can add an array of attachment fields', function () {
    $attachmentField = AttachmentField::create('title', 'value');

    $this->attachment->addFields([$attachmentField]);

    $attachments = $this->attachment->toArray()['fields'];

    $this->assertEquals('title', $attachments[0]['title']);
    $this->assertEquals('value', $attachments[0]['value']);
});

it('can add a action', function () {
    $this->attachment->addAction(['name' => 'button', 'text' => 'a button', 'type' => 'button']);

    $action = $this->attachment->toArray()['actions'][0];

    $this->assertSame('button', $action['name']);
    $this->assertSame('a button', $action['text']);
    $this->assertSame('button', $action['type']);
});

it('can add multiple actions using an associative array', function () {
    $this->attachment->addActions([
        ['name' => 'button1', 'text' => 'button1', 'type' => 'button'],
        ['name' => 'button2', 'text' => 'button2', 'type' => 'button'],
    ]);

    $attachments = $this->attachment->toArray()['actions'];

    $this->assertEquals('button1', $attachments[0]['name']);
    $this->assertEquals('button1', $attachments[0]['text']);
    $this->assertEquals('button', $attachments[0]['type']);

    $this->assertEquals('button2', $attachments[1]['name']);
    $this->assertEquals('button2', $attachments[1]['text']);
    $this->assertEquals('button', $attachments[1]['type']);
});

it('can add an array of attachment actions', function () {
    $attachmentAction = AttachmentAction::create('action', 'click me', 'button');

    $this->attachment->addActions([$attachmentAction]);

    $actions = $this->attachment->toArray()['actions'];

    $this->assertSame('action', $actions[0]['name']);
    $this->assertSame('click me', $actions[0]['text']);
    $this->assertSame('button', $actions[0]['type']);
});
