# PHP-SlackBot [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/lygav/php-slackbot/blob/master/LICENCE)

[![Build Status](https://travis-ci.org/lygav/php-slackbot.svg?branch=master)](https://travis-ci.org/lygav/php-slackbot)



Simple, easy to use, PHP package for sending messages to Slack.  
Send pretty, colourful messages with rich attachments quickly with this friendly API.

Compatible with PHP >= 5.3

## Installation
### Via composer
```json
"require": {
   "lygav/php-slackbot": "0.0.*"
}
```

### Without composer, via PHAR
From the command line, enter into the cloned repository dir and run:
```
php makephar
```
You will see that a new file was created named "phpslackbot.phar".
Then in your application:
```php
include 'path/to/phpslackbot.phar';
```
The rest is the same as when installed with 'composer'  

## Your first message
```PHP
$bot = new Slackbot("https://hooks.slack.com/services/your/incoming/hook");
$bot->text("Hi")->send();
```
## Direct messages
```PHP
$bot->text("Hi all!")
    ->from("username")
    ->toChannel("mychannel")
    ->send();
```
## Create pretty, colorful attachments easily
```PHP
$bot->attach(
    $bot->buildAttachment("fallback text")
    ->enableMarkdown()
    ->setText("We can have *mrkdwn* `code` _italic_ also in attachments")
    )
    ->toGroup("mygroup")
    ->send();
```

## Customise freely
```PHP
$attachment = $bot->buildAttachment("fallback text"/* mandatory by slack */)
    ->setPretext("pretext line")
    ->setText("attachment body text")
    /*
      Human web-safe colors automatically
      translated into HEX equivalent
    */
    ->setColor("lightblue")
    ->setAuthor("me")
    ->addField("short field", "i'm inline", TRUE)
    ->addField("short field 2", "i'm also inline", TRUE)
    ->setImageUrl("http://my-website.com/path/to/image.jpg");

$bot->attach($attachment)->send();
```

## Set/ Override every possible setting
```PHP
$options = array(
            'username' => 'my-bot-name',
            'icon_emoji' => ':icon name:',
            'icon_url' => 'http://someicon.com',
            'channel' => '#test-channel'
        );
        
$bot = new Slackbot($url, $options);
$bot->text("check out bot new icon")->send();

// Choose to override 'last minute' (ex. when dealing with multiple consequtive messages)
$bot->text("check out bot new icon")->send(array("username" => "other-bot-name"));
```

# Advanced Usage

## Use custom transfer handlers
```PHP
$handler = new MockHandler();
$bot     = new SlackBot($url, ['handler' => $handler]);
$bot->text("some text")
    ->from("my-test-bot")
    ->toGroup("bot-testing")
    ->send();
```

