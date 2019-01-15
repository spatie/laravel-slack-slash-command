<?php

return [

    /*
     * At the integration settings over at Slack you can configure the url to which the
     * slack commands are posted. Specify the path component of that url here.
     *
     * For `http://example.com/slack` you would put `slack` here.
     */
    'url' => 'slack',

    /*
     * Slack signs the requests we send you using this secret. Confirm that each request comes from Slack
     * by verifying its unique signature.
     */
    'signing_secret' => env('SLACK_SIGNING_SECRET'),

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
