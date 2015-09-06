<?php

namespace lygav\slackbot\tests;

use lygav\slackbot\Mrkdwn;

class MrkdwnTest extends \PHPUnit_Framework_TestCase
{

    public function userNames()
    {
        return array(
            array('withoutatsign', '<@withoutatsign>'),
            array('@withsign', '<@withsign>')
        );
    }

    /**
     * @dataProvider userNames
     */
    public function testFormatsNameStringIntoUserReference($unformatted, $expected)
    {
        $this->assertEquals(
            $expected,
            Mrkdwn::userRef($unformatted)
        );
    }

    public function chanelNames()
    {
        return array(
            array('withoutatsign', '<#withoutatsign>'),
            array('#withsign', '<#withsign>')
        );
    }

    /**
     * @dataProvider chanelNames
     */
    public function testFormatNameStringIntoChannelReference($unformatted, $expected)
    {
        $this->assertEquals(
            $expected,
            Mrkdwn::channelRef($unformatted)
        );
    }

    public function strings()
    {
        return array(
            array("http://google.com", "search google", "<http://google.com|search google>"),
            array("http://google.com", "search    google", "<http://google.com|search google>"),
            array(" http://google.com ", " search google ", "<http://google.com|search google>"),
            array("http://google.com", "search\ngoogle", "<http://google.com|search google>"),
            array("http://google.com", "search\r\ngoogle", "<http://google.com|search google>"),
        );
    }

    /**
     * @dataProvider strings
     */
    public function testNormalizeText($link, $alias, $expected)
    {
        $this->assertEquals(
            $expected,
            Mrkdwn::link($link, $alias)
        );
    }
}
