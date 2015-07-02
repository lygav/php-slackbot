<?php

namespace lygav\slackbot\Handlers;

use lygav\slackbot\SlackRequest;

interface RequestHandler
{
    public function __invoke(SlackRequest $request);
}