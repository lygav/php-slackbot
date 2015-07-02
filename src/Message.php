<?php

namespace lygav\slackbot;

class Message implements Transferrable
{
    protected $text;
    protected $options     = array();
    protected $attachments = array();

    function __construct($text, array $options = array())
    {
        $this->text    = (string) $text;
        $this->options = $options;
    }

    public function attach(Attachment $attachment)
    {
        array_push($this->attachments, $attachment->serialize());
    }

    public function serialize()
    {
        $ret = array_merge(array('text' => $this->text), $this->options);
        if ( ! empty($this->attachments)) {
            $ret['attachments'] = $this->attachments;
        }
        return $ret;
    }
}