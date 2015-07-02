<?php


namespace lygav\slackbot\tests;

use lygav\slackbot\Attachment;
use lygav\slackbot\Mrkdwn;
use lygav\slackbot\SlackBot;
use lygav\slackbot\tests\Mocks\MockHandler;

/**
 * Class SlackBotTest
 *
 * Mainly for usage demonstration
 *
 * @package lygav\slackbot\tests
 */
class SlackBotTest extends \PHPUnit_Framework_TestCase
{
	public $url = "https://hooks.slack.com/services/your/incoming/hook";

    public function testChooseSuppliedHandler()
	{
		$bot = new SlackBot($this->url, array('handler' => new MockHandler()));
		$bot->attachment(Attachment::fromOptions(array(
			'fallback' => 'fallback text',
			'author_name' => 'tester',
			'color' => 'lightgreen',
			'title' => 'title',
			'text' => 'text'
		)))
			->toGroup("bot-testing")
            ->disableMarkdown()
			->send(array(
				"username" => "my-test-bot"
			));
	}

    /**
     * @expectedException lygav\slackbot\Exceptions\SlackRequestException
     */
    public function testThrowExceptionOnEmptyRequest()
    {
        $bot = new SlackBot($this->url);
        $bot->send();
    }

    public function testSendRealMessage()
    {
        $bot = new SlackBot($GLOBALS['keepgo_hook_url']);
        $attachment = Attachment::fromOptions(array(
            'fallback' => 'fallback bold',
            'author_name' => 'tester',
            'color' => 'lightgreen',
            'title' => 'title',
            'text' => Mrkdwn::link('http://google.com', 'google').' this _italic_ *boldly* `code`',
            'pretext' => 'pretext: '.Mrkdwn::referenceUser('lygav').' _italic_ *boldly* ```pre```'
        ));
        $attachment->enableMarkdown(array('text'));
        $bot->text("*bold* `code` _italic_")
            ->attachment($attachment)
            ->toGroup("bot-testing")
            ->send(array(
                "username" => "markdown-bot"
            ));
    }
}