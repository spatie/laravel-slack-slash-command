<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\Attachment;

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
        $this->attachment->addField(['key' => 'value']);

        $attachment = $this->attachment->toArray()['fields'][0];

        $this->assertEquals('key', $attachment['title']);
        $this->assertEquals('value', $attachment['value']);
    }

    /** @test */
    public function it_can_add_a_multiple_fields()
    {
        $this->attachment->addFields([
            ['key' => 'value'],
            ['key2' => 'value2'],
        ]);

        $attachments = $this->attachment->toArray()['fields'];

        $this->assertEquals('key', $attachments[0]['title']);
        $this->assertEquals('value', $attachments[0]['value']);

        $this->assertEquals('key2', $attachments[1]['title']);
        $this->assertEquals('value2', $attachments[1]['value']);
    }
}
