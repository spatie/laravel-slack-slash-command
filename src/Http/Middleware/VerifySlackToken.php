<?php

namespace Spatie\Skeleton\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Config\Repository;

class VerifySlackToken
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('laravel-slack.slack_tokens');
    }

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        if($this->config[$request->command] != $request->token) {
            throw new Exception('Invalid Slack command token given');
        }

        return $next($request);
    }
}