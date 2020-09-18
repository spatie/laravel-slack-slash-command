<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Str;

class Request
{
    /** @var string */
    public $token;

    /** @var string */
    public $teamId;

    /** @var string */
    public $teamDomain;

    /** @var string */
    public $channelId;

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

    /** @var array */
    public $params;

    public static function createFromIlluminateRequest(IlluminateRequest $illuminateRequest): self
    {
        $selfRequest = collect([
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
        ])->reduce(function (self $request, string $propertyName) use ($illuminateRequest) {
            $request->$propertyName = $illuminateRequest->get(Str::snake($propertyName));

            if ($propertyName == 'command') {

                //remove slash
                $request->command = substr($request->command, 1);
            }

            return $request;
        }, new static());

        $selfRequest->params = $illuminateRequest->route()->parameters();

        return $selfRequest;
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
