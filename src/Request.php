<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request as IlluminateRequest;

class Request
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

    public static function createFromIlluminateRequest(IlluminateRequest $illuminateRequest): Request
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
        ])->reduce(function (Request $request, string $propertyName) use ($illuminateRequest) {
            $request->$propertyName = $illuminateRequest->get(snake_case($propertyName));

            return $request;
        }, new static());
    }

    public function get(string $propertyName): string
    {
        return $this->$propertyName ?? '';
    }

    public function all(): array
    {
        return get_object_vars($this);
    }
}
