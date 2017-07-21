<?php

namespace Spatie\SlashCommand;

class AttachmentAction
{
    /**
     * The required name field of the action.
     *
     * @var string
     */
    protected $name;

    /**
     * The required text field of the action.
     *
     * @var string
     */
    protected $text;

    /**
     * The required type field of the action.
     *
     * @var string
     */
    protected $type;


    /**
     * The value of the action.
     *
     * @var string
     */
    protected $value = '';

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
     * Set the name of the action.
     *
     * @param string $name
     *
     * @return AttachmentAction
     */
    public function setName(string $name): AttachmentAction
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the text of the action.
     *
     * @param string $text
     *
     * @return AttachmentAction
     */
    public function setText(string $text): AttachmentAction
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set the type of the action.
     *
     * @param string $type
     *
     * @return AttachmentAction
     */
    public function setType(string $type): AttachmentAction
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the value of the action.
     *
     * @param string $value
     *
     * @return AttachmentAction
     */
    public function setValue(string $value): AttachmentAction
    {
        $this->value = $value;

        return $this;
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
            'value' => $this->value,
        ];
    }
}
