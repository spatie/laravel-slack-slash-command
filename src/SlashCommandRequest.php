<?php

namespace Spatie\SlashCommand;

use Illuminate\Http\Request;

class SlashCommandRequest
{
    /** Illuminate\Http\Request */
    public $request;

    public static function createForRequest(Request $request)
    {
        self::guardAgainstInvalidSlashCommandRequest($request);

        return new static($request);
    }

    protected static function guardAgainstInvalidSlashCommandRequest($request)
    {
        if (!$request->has('token')) {
            throw InvalidSlashCommandRequest::tokenNotFound();
        }

        if ($request->get('token') != config('laravel-slack.verification_token')) {
            throw InvalidSlashCommandRequest::invalidToken($request->get('token'));
        }
    }

    protected function __construct($request)
    {
        $this->request = $request;
    }
}
