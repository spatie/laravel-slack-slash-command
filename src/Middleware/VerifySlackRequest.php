<?php

namespace Spatie\SlashCommand\Middleware;

use Closure;
use Spatie\SlashCommand\SlackRequestSignature;

class VerifySlackRequest
{
    /** @var SlackRequestSignature */
    private $slackRequestSignature;

    public function __construct(SlackRequestSignature $slackRequestSignature)
    {
        $this->slackRequestSignature = $slackRequestSignature;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = $this->slackRequestSignature->create($request);

        if (!$this->isEqualsSignature($signature, $request->header('X-Slack-Signature'))) {
            abort(401, 'The request had an invalid signature.');
        }

        return $next($request);
    }

    private function isEqualsSignature(string $signature, string $slackSignature): bool
    {
        return $signature === $slackSignature;
    }
}
