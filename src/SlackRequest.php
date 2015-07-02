<?php

namespace lygav\slackbot;

use lygav\slackbot\Exceptions\SlackRequestException;
use lygav\slackbot\Message;

class SlackRequest
{
    private $url;
    private $body;

    function __construct($url, Message $message)
    {
        $this->url  = $url;
        $this->setBody($message->serialize());
    }

    public function body()
    {
        return $this->payload_for($this->body);
    }

    private function setBody(array $body)
    {
        $empty_body = array('text' => '');
        if ($body === $empty_body) {
            throw new SlackRequestException("Trying to construct SlackRequest with empty message");
        }
        $this->body = $body;
    }

    public function url()
    {
        return $this->url;
    }

    private function payload_for($body)
    {
        return http_build_query(
            array("payload" => json_encode($body))
        );
    }
}