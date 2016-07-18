<?php

namespace Spatie\SlashCommand;

class AttachmentField
{
    /**
     * The required title field of the field.
     *
     * @var string
     */
    protected $title;

    /**
     * The required value of the field.
     *
     * @var string
     */
    protected $value;

    /**
     * Whether the value is short enough to fit side by side with
     * other values.
     *
     * @var bool
     */
    protected $short = false;

    public static function create(array $attributes)
    {
        return new static($attributes);
    }

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        if (isset($attributes['title'])) {
            $this->setTitle($attributes['title']);
        }

        if (isset($attributes['value'])) {
            $this->setValue($attributes['value']);
        }

        if (isset($attributes['short'])) {
            $this->setShort($attributes['short']);
        }
    }

    /**
     * Set the title of the field.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the value of the field.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function displaySideBySide()
    {
        $this->short = true;

        return $this;
    }

    public function doNotDisplaySideBySide()
    {
        $this->short = false;

        return $this;
    }

    /**
     * Get the array representation of this attachment field.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->value,
            'short' => $this->short,
        ];
    }
}
