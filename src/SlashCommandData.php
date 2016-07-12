<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request;

class SlashCommandData
{
    /** @var string */
    public $token;

    /** @var string */
    public $teamId;

    /** @var string */
    public $teamDomain;

    /** @var string */
    public $channelName;

    /** @var string */
    public $userId;

    /** @var string */
    public $userName;

    /** @var string */
    public $command;

    /** @var string */
    public $text;

    /** @var string */
    public $reponseUrl;

    public static function createForRequest(Request $request): SlashCommandData
    {
        return collect([
            'token',
            'team_id',
            'team_domain',
            'channel_id',
            'channel_name',
            'user_id',
            'user_name',
            'command',
            'text',
            'reponse_url'
        ])->reduce(function (SlashCommandData $slashCommandData, string $slackFieldName) use ($request) {
            $propertyName = camel_case($slackFieldName);

            $slashCommandData->$propertyName = $request->get($slackFieldName);
            
            return $slashCommandData;
        }, new static());
    }
}
