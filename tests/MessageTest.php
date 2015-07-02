<?php

namespace lygav\slackbot\tests;

use lygav\slackbot\Attachment;
use lygav\slackbot\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{

    public function testSerializePlainTextBody()
    {
        $text = "hello worlds";
        $message = new Message($text);
        $this->assertEquals(
            array("text" => $text),
            $message->serialize()
        );
    }

    public function testSerializeWithOptions()
    {
        $text = "hello world";
        $options = array(
            'username' => 'my-bot-name',
            'icon_emoji' => ':icon name:',
            'icon_url' => 'http://someicon.com',
            'channel' => '#test-channel'
        );
        $message = new Message($text, $options);
        $this->assertEquals(
            array_merge(array("text" => $text), $options),
            $message->serialize()
        );
    }

    public function testSerializeWithAttachment()
    {
        $text = "hello worlds";
        $message = new Message($text);
        $message->attach(new Attachment('plain text fallback'));
        $this->assertEquals(
            array(
                "text" => $text,
                "attachments" => array(
                    array("fallback" => "plain text fallback")
                )
            ),
            $message->serialize()
        );
    }


}
