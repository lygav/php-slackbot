<?php

namespace lygav\slackbot;

class Mrkdwn 
{
    public static function referenceUser($slackname)
    {
        return sprintf("<%s>", strpos($slackname, "@") === 0 ? : "@".$slackname);
    }

    public static function referenceChannel($channel)
    {
        return sprintf("<%s>", strpos($channel, "#") === 0 ? : "#".$channel);
    }

    public static function code($text)
    {
        return sprintf("`%s`", $text);
    }

    public static function pre($text)
    {
        return sprintf("```%s```", $text);
    }

    public static function link($url, $alias = NULL)
    {
        return is_null($alias) ? $url : sprintf("<%s|%s>", $url, $alias);
    }
}