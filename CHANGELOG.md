# Changelog

All Notable changes to `laravel-slack` will be documented in this file

## 1.1.1 - 2016-10-05

- all exceptions will be converted to proper Illuminate responses
- a `SignatureHandler` will now be validated in the controller

## 1.1.0 - 2016-10-04

- added the `Help` handler
- added support for wildcards in `SignatureHandler`
- all exceptions now inherited from `Spatie\SlashCommand\Exceptions\SlashCommandExpception`
- exceptions will now result in a proper error in your Slack channel instead of dumping out some html

## 1.0.1 - 2016-07-27
- fixed dependency injection in `handle` method of queud jobs

## 1.0.0 - 2016-07-20
- initial release
