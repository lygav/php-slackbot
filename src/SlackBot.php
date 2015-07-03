<?php


namespace lygav\slackbot;

use lygav\slackbot\Exceptions\SlackRequestException;
use lygav\slackbot\Handlers\CurlHandler;

class SlackBot
{
	private $text;
	private $handler;
	private $webhook_url;
	private $attachments     = array();
	private $global_options  = array();
	private $request_options = array();

	function __construct($webhook_url, array $options = array())
	{
		$this->webhook_url = $webhook_url;
		if (isset($options['handler'])) {
			$this->handler = $options['handler'];
			unset($options['handler']);
		}
		$this->global_options = $options;
	}

	public function text($text)
	{
		$this->text = $text;
		return $this;
	}

	public function from($name)
	{
		$this->setRequestOption('username', $name);
		return $this;
	}

	public function attach(Attachment $attachment)
	{
		array_push($this->attachments, $attachment);
		return $this;
	}

	public function buildAttachment($fallback_text)
	{
		return new Attachment($fallback_text);
	}

	public function toChannel($name)
	{
		$this->setRequestChannel($name);
		return $this;
	}

	public function toGroup($name)
	{
		$this->setRequestChannel($name);
		return $this;
	}

	public function toPerson($name)
	{
		$this->setRequestChannel($name, TRUE);
		return $this;
	}

	public function send(array $options = array())
	{
		$options = array_replace($this->global_options, $this->request_options, $options);
		$message = new Message($this->text, $options);
		if ( ! empty($this->attachments)) {
			array_map(array($message, 'attach'), $this->attachments);
		}
		$request = new SlackRequest($this->webhook_url, $message);
		$this->transfer($request);
		$this->reset();
	}

	public function disableMarkdown()
	{
		$this->setRequestOption('mrkdwn', FALSE);
		return $this;
	}

	public function enableMarkdown()
	{
		$this->setRequestOption('mrkdwn', TRUE);
		return $this;
	}

	private function transfer(SlackRequest $request)
	{
		$result = call_user_func($this->handler(), $request);
		if ($result !== 'ok') {
			throw new SlackRequestException($result);
		} else {
			return $result;
		}
	}

	private function reset()
	{
		$this->attachments     = array();
		$this->text            = "";
		$this->request_options = array();
	}

	private function handler()
	{
		return $this->handler ? : new CurlHandler();
	}

	private function setRequestChannel($name, $private = FALSE)
	{
		if ($private) {
			$this->setRequestOption('channel', strpos($name, "@") === 0 ? : "@".$name);
		} else {
			$this->setRequestOption('channel', strpos($name, "#") === 0 ? : "#".$name);
		}
	}

	private function setRequestOption($name, $value)
	{
		$this->request_options[$name] = $value;
	}
}