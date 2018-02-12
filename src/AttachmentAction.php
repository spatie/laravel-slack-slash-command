<?php

namespace Spatie\SlashCommand;

use Spatie\SlashCommand\Exceptions\InvalidConfirmationHash;

class AttachmentAction
{
    const STYLE_DEFAULT = 'default';
    const STYLE_PRIMARY = 'primary';
    const STYLE_DANGER = 'danger';

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

    /**
     * The style of the action.
     *
     * @var string
     */
    protected $style = self::STYLE_DEFAULT;

    /**
     * An optional confirmation
     * dialog for the action.
     *
     * @var null|array
     */
    protected $confirmation = null;

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
    public function setName(string $name): self
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
    public function setText(string $text): self
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
    public function setType(string $type): self
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
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set the style of the action.
     *
     * @param string $style
     *
     * @return AttachmentAction
     */
    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Sets the confirmation hash for the action.
     *
     * @param array $confirmation
     *
     * @return \Spatie\SlashCommand\AttachmentAction
     * @throws \Spatie\SlashCommand\Exceptions\InvalidConfirmationHash
     */
    public function setConfirmation(array $confirmation)
    {
        if (! array_key_exists('text', $confirmation)) {
            throw InvalidConfirmationHash::missingTextField();
        }

        $this->confirmation = $confirmation;

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
            'style' => $this->style,
            'confirm' => $this->confirmation,
        ];
    }
}
