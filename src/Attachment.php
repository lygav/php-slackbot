<?php


namespace lygav\slackbot;

use lygav\slackbot\Exceptions\SlackBotException;

class Attachment implements Transferrable
{
    private $options = array();

    function __construct($fallback)
    {
        $this->options["fallback"] = (string) $fallback;
    }

    public static function fromOptions(array $options)
    {
        if ( ! isset($options['fallback'])) {
            throw new SlackBotException("'fallback' is mandatory for
            attachments");
        }
        $attachment = new self($options['fallback']);
        if (isset($options['color'])) {
            $attachment->setColor($options['color']);
            unset($options['color']);
        }
        $attachment->options = array_replace($attachment->options, $options);
        return $attachment;
    }

    public function serialize()
    {
        return $this->options;
    }

    public function enableMarkdown(array $in_options = array())
    {
        if (empty($in_options)) {
            $this->applyOption("mrkdwn_in", array_keys($this->options));
        } else {
            $this->applyOption("mrkdwn_in", $in_options);
        }
        return $this;
    }

    public function setTitle($title)
    {
        $this->applyOption('title', $title);
        return $this;
    }

    public function addField($title, $text, $is_short = FALSE)
    {
        if ( ! isset($this->options['fields'])
            OR ! is_array($this->options['fields'])
        ) {
            $this->options['fields'] = array();
        }
        array_push($this->options['fields'], array(
            'title' => $title,
            'value' => $text,
            'short' => $is_short
        ));
        return $this;
    }

    public function setAuthor($name, $link = NULL, $icon = NULL)
    {
        $this->applyOption('author_name', $name);
        $this->applyOption('author_link', $link);
        $this->applyOption('author_icon', $icon);
        return $this;
    }

    public function setColor($color)
    {
        $this->applyOption('color', WebColors::human2hex($color) ? : $color);
        return $this;
    }

    public function setText($text)
    {
        $this->applyOption('text', $text);
        return $this;
    }

    /**
     * @param string $pretext
     */
    public function setPretext($pretext)
    {
        $this->applyOption('pretext', $pretext);
        return $this;
    }

    /**
     * @param null $image_url
     */
    public function setImageUrl($image_url)
    {
        $this->applyOption('image_url', $image_url);
        return $this;
    }

    /**
     * @param null $thumb_url
     */
    public function setThumbUrl($thumb_url)
    {
        $this->applyOption('thumb_url', $thumb_url);
        return $this;
    }

    private function applyOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }
}