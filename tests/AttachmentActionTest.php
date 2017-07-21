<?php

namespace Spatie\SlashCommand\Test;

use Spatie\SlashCommand\AttachmentAction;

class AttachmentActionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_set_a_value()
    {
        $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                                            ->setValue('value');

        $action = $attachmentAction->toArray();

        $this->assertSame('action', $action['name']);
        $this->assertSame('an action', $action['text']);
        $this->assertSame('button', $action['type']);
        $this->assertSame('value', $action['value']);
    }

    /** @test */
    public function it_can_set_a_style()
    {
        $attachmentAction = AttachmentAction::create('action', 'an action', 'button')
                                            ->setStyle(AttachmentAction::STYLE_PRIMARY);

        $action = $attachmentAction->toArray();

        $this->assertSame('action', $action['name']);
        $this->assertSame('an action', $action['text']);
        $this->assertSame('button', $action['type']);
        $this->assertSame('primary', $action['style']);
    }
}
