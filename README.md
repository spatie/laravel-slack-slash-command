<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=laravel-slack-slash-command">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/laravel-slack-slash-command/html/dark.webp">
        <img alt="Logo for laravel-slack-slash-command" src="https://spatie.be/packages/header/laravel-slack-slash-command/html/light.webp">
      </picture>
    </a>

<h1>Making a Laravel app respond to Slack commands</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-slash-command)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-slack-slash-command/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-slack-slash-command)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-slack-slash-command)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-slash-command)
    
</div>

This package makes it easy to make your Laravel app respond to [Slack's Slash commands](https://api.slack.com/slash-commands). 

Once you've setup your Slash command over at Slack and installed this package into a Laravel app you can create handlers that can handle a slash command. Here's an example of such a handler that will send a response back to slack.

```php
namespace App\SlashCommandHandlers;

use App\SlashCommand\BaseHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class CatchAll extends BaseHandler
{
    /**
     * If this function returns true, the handle method will get called.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        return true;
    }

    /**
     * Handle the given request. Remember that Slack expects a response
     * within three seconds after the slash command was issued. If
     * there is more time needed, dispatch a job.
     * 
     * @param \Spatie\SlashCommand\Request $request
     * 
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        return $this->respondToSlack("You have typed this text: `{$request->text}`");
    }
}
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-slack-slash-command.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-slack-slash-command)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-slack-slash-command
```

This service provider must be installed.

```php
// config/app.php
'providers' => [
    ...
    Spatie\SlashCommand\SlashCommandServiceProvider::class,
];
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="Spatie\SlashCommand\SlashCommandServiceProvider"
```

This is the contents of the published file:

```php
return [

    /*
     * At the integration settings over at Slack you can configure the url to which the 
     * slack commands are posted. Specify the path component of that url here. 
     * 
     * For `http://example.com/slack` you would put `slack` here.
     */
    'url' => 'slack',

    /*
     * The token generated by Slack with which to verify if a incoming slash command request is valid.
     */
    'token' => env('SLACK_SLASH_COMMAND_VERIFICATION_TOKEN'),
    
    /*
     * The signing_secret generated by Slack with which to verify if a incoming slash command request is valid.
     */
    'signing_secret' => env('SLACK_SIGNING_SECRET'),

    /*
     * Verify requests from slack with signing_secret signature
     */
    'verify_with_signing' => false,

    /*
     * The handlers that will process the slash command. We'll call handlers from top to bottom
     * until the first one whose `canHandle` method returns true.
     */
    'handlers' => [
        //add your own handlers here


        //this handler will display instructions on how to use the various commands.
        Spatie\SlashCommand\Handlers\Help::class,

        //this handler will respond with a `Could not handle command` message.
        Spatie\SlashCommand\Handlers\CatchAll::class,
    ],
];

```
Change `verify_with_signing` parameter to verify requests from slack by `signing_secret`:
```php
// config/laravel-slack-slash-command.php
'verify_with_signing' => true
```

## Documentation
You'll find the documentation on [https://docs.spatie.be/laravel-slack-slash-command](https://docs.spatie.be/laravel-slack-slash-command).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the media library? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-slack-slash-command/issues), we'll try to address it as soon as possible.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The message and attachment functionalities were heavily inspired on [Regan McEntyre's Slack package](https://github.com/maknz/slack).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
