<?php


namespace lygav\slackbot\tests;

use lygav\slackbot\Attachment;
use lygav\slackbot\WebColors;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{

    public function testCanCreateBasicWithFallbackTextOnly()
    {
        $attachment = new Attachment("test fallback text");
        $this->assertEquals(array ('fallback' => 'test fallback text'), $attachment->serialize());
    }

    public function testCanCreateFromFullOptionsArray()
    {
        $options = array(
            'fallback' => 'fallback test',
            'color' => '#000000',
            'author_name' => 'bobby',
            'author_link' => 'http://flickr.com/bobby/',
            'author_icon' => 'http://flickr.com/icons/bobby.jpg',
            'title' => 'Optional title',
            'text'  => 'optional text',
            'pretext' => 'optional pretext',
            'image_url' => 'http://my-website.com/path/to/image.jpg',
            'thumb_url' => 'http://example.com/path/to/thumb.png'
        );
        $attachment = Attachment::fromOptions($options);
        $this->assertEquals($options, $attachment->serialize());
    }

    public function testEnableMarkdownForAllOptions()
    {
        $options = array(
            'fallback' => 'fallback text',
            'text' => 'my bold *text*',
            'pretext' => 'some _italic_ markdown here'
        );
        $attachment = Attachment::fromOptions($options);
        $attachment->enableMarkdown();
        $this->assertEquals(array (
            'fallback' => 'fallback text',
            'text' => 'my bold *text*',
            'pretext' => 'some _italic_ markdown here',
            'mrkdwn_in' =>
                array (
                    'fallback',
                    'text',
                    'pretext',
                ),
        ), $attachment->serialize());
    }

    public function testEnableMarkdownOnlyForSelectedOptions()
    {
        $options = array(
            'fallback' => 'fallback text',
            'text' => 'my bold *text*',
            'pretext' => 'some _italic_ markdown here'
        );
        $attachment = Attachment::fromOptions($options);
        $attachment->enableMarkdown(array('text'));
        $this->assertEquals(array (
            'fallback' => 'fallback text',
            'text' => 'my bold *text*',
            'pretext' => 'some _italic_ markdown here',
            'mrkdwn_in' =>
                array (
                    'text'
                ),
        ), $attachment->serialize());
    }

    public function testReplaceHumanColorNameWithHexCode()
    {
        $options = array('fallback' => 'fallback text', 'color' => 'black');
        $attachment = Attachment::fromOptions($options);
        $options['color'] = WebColors::human2hex($options['color']);
        $this->assertEquals($options, $attachment->serialize());
    }

}
