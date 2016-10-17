<?php

namespace Spatie\SlashCommand;

use DateTime;
use Illuminate\Support\Collection;
use Spatie\SlashCommand\Exceptions\FieldCannotBeAdded;

class Attachment
{
    const COLOR_GOOD = 'good';
    const COLOR_WARNING = 'warning';
    const COLOR_DANGER = 'danger';

    /**
     * The fallback text to use for clients that don't support attachments.
     *
     * @var string
     */
    protected $fallback;

    /**
     * Optional text that should appear within the attachment.
     *
     * @var string
     */
    protected $text = '';

    /**
     * Optional image that should appear within the attachment.
     *
     * @var string
     */
    protected $imageUrl;

    /**
     * Optional thumbnail that should appear within the attachment.
     *
     * @var string
     */
    protected $thumbUrl;

    /**
     * Optional text that should appear above the formatted data.
     *
     * @var string
     */
    protected $preText;

    /**
     * Optional title for the attachment.
     *
     * @var string
     */
    protected $title;

    /**
     * Optional title link for the attachment.
     *
     * @var string
     */
    protected $titleLink;

    /**
     * Optional author name for the attachment.
     *
     * @var string
     */
    protected $authorName;

    /**
     * Optional author link for the attachment.
     *
     * @var string
     */
    protected $authorLink;

    /**
     * Optional author icon for the attachment.
     *
     * @var string
     */
    protected $authorIcon;

    /**
     * The color to use for the attachment.
     *
     * @var string
     */
    protected $color;

    /**
     * The text to use for the attachment footer.
     *
     * @var string
     */
    protected $footer;

    /**
     * The icon to use for the attachment footer.
     *
     * @var string
     */
    protected $footerIcon;

    /**
     * The timestamp to use for the attachment.
     *
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * The fields of the attachment.
     *
     * @var Collection
     */
    protected $fields;

    public static function create()
    {
        return new static();
    }

    public function __construct()
    {
        $this->fields = new Collection();
    }

    /**
     * Set the fallback text.
     *
     * @param string $fallback
     *
     * @return $this
     */
    public function setFallback(string $fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the optional text to appear within the attachment.
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set the optional image to appear within the attachment.
     *
     * @param string $imageUrl
     *
     * @return $this
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Set the optional thumbnail to appear within the attachment.
     *
     * @param string $thumbUrl
     *
     * @return $this
     */
    public function setThumbUrl(string $thumbUrl)
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * Set the text that should appear above the formatted data.
     *
     * @param string $preText
     *
     * @return $this
     */
    public function setPreText(string $preText)
    {
        $this->preText = $preText;

        return $this;
    }

    /**
     * Set the color to use for the attachment.
     *
     * @param string $color
     *
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the footer text to use for the attachment.
     *
     * @param string $footer
     *
     * @return $this
     */
    public function setFooter(string $footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Set the footer icon to use for the attachment.
     *
     * @param string $footerIcon
     *
     * @return $this
     */
    public function setFooterIcon(string $footerIcon)
    {
        $this->footerIcon = $footerIcon;

        return $this;
    }

    /**
     * Set the timestamp to use for the attachment.
     *
     * @param \DateTime $timestamp
     *
     * @return $this
     */
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Set the title to use for the attachment.
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
     * Set the title link to use for the attachment.
     *
     * @param string $titleLink
     *
     * @return $this
     */
    public function setTitleLink(string $titleLink)
    {
        $this->titleLink = $titleLink;

        return $this;
    }

    /**
     * Set the author name to use for the attachment.
     *
     * @param string $authorName
     *
     * @return $this
     */
    public function setAuthorName(string $authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Set the auhtor link to use for the attachment.
     *
     * @param string $authorLink
     *
     * @return $this
     */
    public function setAuthorLink(string $authorLink)
    {
        $this->authorLink = $authorLink;

        return $this;
    }

    /**
     * Set the author icon to use for the attachment.
     *
     * @param string $authorIcon
     *
     * @return $this
     */
    public function setAuthorIcon(string $authorIcon)
    {
        $this->authorIcon = $authorIcon;

        return $this;
    }

    /**
     * Set the fields for the attachment.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->clearFields();

        collect($fields)->each(function ($field) {
            $this->addField($field);
        });

        return $this;
    }

    /**
     * Add a field to the attachment.
     *
     * @param \Spatie\SlashCommand\AttachmentField|array $field
     *
     * @return $this
     *
     * @throws \Spatie\SlashCommand\Exceptions\FieldCannotBeAdded
     */
    public function addField($field)
    {
        if (! is_array($field) && ! $field instanceof AttachmentField) {
            throw FieldCannotBeAdded::invalidType();
        }

        if (is_array($field)) {
            $title = array_keys($field)[0];
            $value = $field[$title];

            $field = AttachmentField::create($title, $value);
        }

        $this->fields->push($field);

        return $this;
    }

    /**
     * Clear all fields for this attachment.
     *
     * @return $this
     */
    public function clearFields()
    {
        $this->fields = new Collection();

        return $this;
    }

    /**
     * Convert this attachment to its array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'fallback' => $this->fallback,
            'text' => $this->text,
            'pretext' => $this->preText,
            'color' => $this->color,
            'footer' => $this->footer,
            'footer_icon' => $this->footer,
            'ts' => $this->timestamp ? $this->timestamp->getTimestamp() : null,
            'image_url' => $this->imageUrl,
            'thumb_url' => $this->thumbUrl,
            'title' => $this->title,
            'title_link' => $this->titleLink,
            'author_name' => $this->authorName,
            'author_link' => $this->authorLink,
            'author_icon' => $this->authorIcon,
            'fields' => $this->fields->map(function (AttachmentField $field) {
                return $field->toArray();
            })->toArray(),
        ];
    }
}
