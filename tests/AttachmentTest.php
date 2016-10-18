<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;

class AttachmentTest extends TestCase
{
    /** @var Attachment */
    protected $attachment;

    public function setUp()
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
}
