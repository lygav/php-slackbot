<?php

namespace lygav\slackbot;

interface Transferrable
{
    /**
     * Serialize the instance into an array
     * @return array
     */
    public function serialize();
}