<?php


namespace lygav\slackbot;

use lygav\slackbot\Exceptions\SlackRequestException;
use lygav\slackbot\Handlers\CurlHandler;

class SlackBot
{
    private $webhook_url;
    private $text;
    private $options = array('mrkdwn' => true);
    private $attachments = array();
    private $handler;

    function __construct($webhook_url, array $options = array())
    {
        $this->webhook_url = $webhook_url;
        if (isset($options['handler'])) {
            $this->handler = $options['handler'];
            unset($options['handler']);
        }
	    $this->applyOptions($options);
    }

	public function text($text)
	{
		$this->text = $text;
		return $this;
	}

	public function toChannel($name)
	{
		$this->setChannel($name);
		return $this;
	}

	public function toGroup($name)
	{
		$this->setChannel($name);
		return $this;
	}

	public function toPerson($name)
	{
		$this->setChannel($name, TRUE);
		return $this;
	}

	public function attachment(Attachment $attachment)
	{
		array_push($this->attachments, $attachment);
		return $this;
	}

    public function send(array $options = array())
    {
		$this->applyOptions($options);
	    $message = new Message($this->text, $this->options);
	    if ( ! empty($this->attachments)) {
		    array_map(array($message, 'attach'), $this->attachments);
	    }
        $request = new SlackRequest($this->webhook_url, $message);
        $this->transfer($request);
    }

    public function disableMarkdown()
    {
        $this->options['mrkdwn'] = FALSE;
        return $this;
    }

    public function enableMarkdown()
    {
        $this->options['mrkdwn'] = TRUE;
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

    private function handler()
    {
        return $this->handler ? : new CurlHandler();
    }

	private function setChannel($name, $private = FALSE)
	{
		if ($private) {
			$this->options['channel'] = strpos($name, "@") === 0 ? : "@".$name ;
		} else {
			$this->options['channel'] = strpos($name, "#") === 0 ? : "#".$name ;
		}
	}
	private function applyOptions(array $options)
	{
		$this->options = array_replace($this->options, $options);
	}
}