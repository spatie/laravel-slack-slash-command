<?php

namespace Spatie\SlashCommand;

class AttachmentAction
{
    /**
     * The required name field of the action.
     *
     * @var string
     */
    private $name;

    /**
     * The required text field of the action.
     *
     * @var string
     */
    private $text;

    /**
     * The required type field of the action.
     *
     * @var string
     */
    private $type;

    public static function create($name, $text, $type)
    {
        return new static($name, $text, $type);
    }

    public function __construct(string $name, string $text, string $type)
    {
        $this->name = $name;
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * Convert this action to its array representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
            'type' => $this->type,
        ];
    }
}
