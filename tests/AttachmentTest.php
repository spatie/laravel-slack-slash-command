<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentAction;
use Spatie\SlashCommand\AttachmentField;

class AttachmentTest extends TestCase
{
    /** @var Attachment */
    protected $attachment;

    public function setUp(): void
    {
        $this->attachment = new Attachment();
    }

    /** @test */
    public function it_can_add_a_field()
    {
        $this->attachment->addField(['title' => 'value']);

        $attachment = $this->attachment->toArray()['fields'][0];

        $this->assertEquals('title', $attachment['title']);
        $this->assertEquals('value', $attachment['value']);
    }

    /** @test */
    public function it_can_add_a_multiple_fields_using_an_associative_array()
    {
        $this->attachment->addFields([
            'title' => 'value',
            'title2' => 'value2',
        ]);

        $attachments = $this->attachment->toArray()['fields'];

        $this->assertEquals('title', $attachments[0]['title']);
        $this->assertEquals('value', $attachments[0]['value']);

        $this->assertEquals('title2', $attachments[1]['title']);
        $this->assertEquals('value2', $attachments[1]['value']);
    }

    /** @test */
    public function it_can_add_an_array_of_attachment_fields()
    {
        $attachmentField = AttachmentField::create('title', 'value');

        $this->attachment->addFields([$attachmentField]);

        $attachments = $this->attachment->toArray()['fields'];

        $this->assertEquals('title', $attachments[0]['title']);
        $this->assertEquals('value', $attachments[0]['value']);
    }

    /** @test */
    public function it_can_add_a_action()
    {
        $this->attachment->addAction(['name' => 'button', 'text' => 'a button', 'type' => 'button']);

        $action = $this->attachment->toArray()['actions'][0];

        $this->assertSame('button', $action['name']);
        $this->assertSame('a button', $action['text']);
        $this->assertSame('button', $action['type']);
    }

    /** @test */
    public function it_can_add_multiple_actions_using_an_associative_array()
    {
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
    }

    /** @test */
    public function it_can_add_an_array_of_attachment_actions()
    {
        $attachmentAction = AttachmentAction::create('action', 'click me', 'button');

        $this->attachment->addActions([$attachmentAction]);

        $actions = $this->attachment->toArray()['actions'];

        $this->assertSame('action', $actions[0]['name']);
        $this->assertSame('click me', $actions[0]['text']);
        $this->assertSame('button', $actions[0]['type']);
    }
}
