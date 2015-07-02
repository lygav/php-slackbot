<?php


namespace lygav\slackbot\tests;

use lygav\slackbot\Attachment;
use lygav\slackbot\Message;
use lygav\slackbot\SlackRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function testCreatePayloadForTextMessage()
    {
        $request = new SlackRequest('http://url.com', new Message('hello world'));

        $this->assertEquals('payload={"text":"hello world"}',
        urldecode($request->body()));
    }

    public function testCreatePayloadForMessageWithAttachment()
    {
        $message = new Message('hello world');
        $message->attach(new Attachment("fallback text"));
        $request = new SlackRequest('http://url.com', $message);

        $this->assertEquals('payload={"text":"hello world","attachments":[{"fallback":"fallback text"}]}',
            urldecode($request->body()));
    }

    public function emptyMessageProvider()
    {
        return array(
            array(new Message(NULL)),
            array(new Message(""))
        );
    }

    /**
     * @dataProvider emptyMessageProvider
     * @expectedException lygav\slackbot\Exceptions\SlackRequestException
     */
    public function testExceptionOnEmptyMessage(Message $message)
    {
        new SlackRequest("www.url.com", $message);
    }
}
