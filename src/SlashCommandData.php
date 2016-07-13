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
    public $responseUrl;

    public static function createForRequest(Request $request): SlashCommandData
    {
        return collect([
            'token',
            'teamId',
            'teamDomain',
            'channelId',
            'channelName',
            'userId',
            'userName',
            'command',
            'text',
            'responseUrl',
        ])->reduce(function (SlashCommandData $slashCommandData, string $propertyName) use ($request) {
            $slashCommandData->$propertyName = $request->get(snake_case($propertyName));
            return $slashCommandData;
        }, new static());
    }
}
