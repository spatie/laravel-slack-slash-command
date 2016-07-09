# Make a Laravel app respond to a slash command from Slack

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-slash-command)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-slack-slash-command/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-slack-slash-command)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/4c293b8a-4e83-4e72-b2ac-949497b92ae3.svg?style=flat-square)](https://insight.sensiolabs.com/projects/4c293b8a-4e83-4e72-b2ac-949497b92ae3)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-slack-slash-command)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-slack-slash-command.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-slack-slash-command)

This package makes it easy to let your Laravel app respond to [Slash commands](https://api.slack.com/slash-commands) from Slack.

Code examples coming soon...

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

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
coming soon...
```


## Usage

Coming soon

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
