<?php

namespace lygav\slackbot\tests\Mocks;

use lygav\slackbot\Handlers\RequestHandler;
use lygav\slackbot\SlackRequest;

class MockHandler implements RequestHandler
{
	private $request;
    public function __invoke(SlackRequest $request)
    {
        $this->request = $request;
        return 'ok';
    }

	public function lastRequest()
	{
		return $this->request;
	}
}