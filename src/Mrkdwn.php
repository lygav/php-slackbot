<?php

namespace lygav\slackbot;

class Mrkdwn
{
    public static function userRef($slackname)
    {
        $slackname = self::normalize($slackname);
        return sprintf("<%s>", (strpos($slackname, "@") === 0 ? "" : "@") . $slackname);
    }

    public static function channelRef($channel)
    {
        $channel = self::normalize($channel);
        return sprintf("<%s>", (strpos($channel, "#") === 0 ? "" : "#") . $channel);
    }

    public static function code($text)
    {
        return sprintf("`%s`", $text);
    }

    public static function pre($text)
    {
        return sprintf("```%s```", $text);
    }

    public static function link($url, $alias)
    {
        $url = self::normalize($url);
        $alias = self::normalize($alias);
        return sprintf("<%s|%s>", $url, $alias);
    }

    private static function normalize($string)
    {
        return preg_replace(
            array("#(^\s|\s$)+#", "#[\r\n\s\s]+#"), array("", " "), $string);
    }
}