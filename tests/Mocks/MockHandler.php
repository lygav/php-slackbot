<?php

namespace lygav\slackbot\tests\Mocks;

use lygav\slackbot\Handlers\RequestHandler;
use lygav\slackbot\SlackRequest;

class MockHandler implements RequestHandler
{
    public function __invoke(SlackRequest $request)
    {
        var_export($request);
        return 'ok';
    }
}