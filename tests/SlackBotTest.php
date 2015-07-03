<?php


namespace lygav\slackbot\tests;

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
	public $handler;
	public $slackbot;

	protected function setUp()
	{
		$this->handler  = new MockHandler();
		$this->slackbot = new SlackBot($this->url, array('handler' => $this->handler));
	}

	public function testSupplyCustomHandler()
	{
		$handler = new MockHandler();
		$bot     = new SlackBot($this->url, array('handler' => $handler));
		$bot->text("some text")
			->from("my-test-bot")
			->toGroup("bot-testing")
			->send();
		var_export($handler->lastRequest());
	}

	public function testOverrideOptionsOnSend()
	{
		$slack = $this->defaultTestBot();
		$slack->text("some text")->send(array(
			"username" => "overriden-bot-name"
		));
	}

	public function testSendMessageWithSimpleAttachment()
	{
		$slack = $this->defaultTestBot();
		$slack->text("Markdown formatted text with *bold* `code` _italic_")
			->attach(
				$slack->buildAttachment("fallback text")
					->enableMarkdown()
					->setText("We can have *mrkdwn* `code` _italic_ also in attachments")
			)
			->toGroup("bot-testing")
			->send();
	}

	public function testCreateCompleteAttachments()
	{
		$slack      = $this->defaultTestBot();
		$attachment = $slack->buildAttachment("fallback text"/* mandatory by slack */)
			->setPretext("pretext line")
			->setText("attachment body text")
			/*
			  Human web-safe colors automatically
			  translated into HEX equivalent
			*/
			->setColor("lightblue")
			->setAuthor("tester")
			->addField("short field", "i'm inline", TRUE)
			->addField("short field 2", "i'm also inline", TRUE)
			->setImageUrl("http://my-website.com/path/to/image.jpg");

		$slack->attach($attachment)->send();
	}

	/**
	 * @expectedException lygav\slackbot\Exceptions\SlackRequestException
	 */
	public function testThrowExceptionOnEmptyRequest()
	{
		$bot = new SlackBot($this->url);
		$bot->send();
	}

	/**
	 * @return SlackBot
	 */
	public function defaultTestBot()
	{
		return $this->slackbot;
	}
}