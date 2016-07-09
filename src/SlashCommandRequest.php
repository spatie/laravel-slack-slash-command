<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request;

class SlashCommandRequest
{
    /** Illuminate\Http\Request */
    public $request;

    public static function createForRequest(Request $request, array $commandConfig)
    {
        self::guardAgainstInvalidSlashCommandRequest($request, $commandConfig);

        return new static($request);
    }

    protected static function guardAgainstInvalidSlashCommandRequest($request, array $commandConfig)
    {
        if (!$request->has('token')) {
            throw InvalidSlashCommandRequest::tokenNotFound();
        }

        if ($request->get('token') != $commandConfig['token']) {
            throw InvalidSlashCommandRequest::invalidToken($request->get('token'));
        }
    }

    protected function __construct($request)
    {
        $this->request = $request;
    }
}
