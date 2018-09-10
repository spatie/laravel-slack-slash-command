# Changelog

All notable changes to `laravel-slack` will be documented in this file

## 1.6.2 - 2018-09-10

- add support for L5.7

## 1.6.1 - 2018-08-04

- fix `footerIcon`

## 1.6.0 - 2018-02-12

- add `useMarkdown`

## 1.5.1 - 2018-02-12

- add support for L5.6

## 1.5.0 - 2017-09-13

- add `withAttachments`

## 1.4.1 - 2017-07-29

- add `setCallbackId`

## 1.4.0 - 2017-07-29

- add support adding actions to attachments

## 1.3.0 - 2017-01-24

- add support for Laravel 5.4

## 1.2.0 - 2016-11-19

- add support for multiple configured tokens

## 1.1.3 - 2016-10-18

- fixes adding an `AttachmendField` to an `Attachment`

## 1.1.2 - 2016-10-17

- fixes passing an array to `addFields`

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
