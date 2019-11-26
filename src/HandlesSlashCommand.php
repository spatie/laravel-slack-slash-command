<?php

namespace Spatie\SlashCommand;

interface HandlesSlashCommand
{
    public function getRequest(): Request;

    public function respondToSlack(string $text = ''): Response;
}
