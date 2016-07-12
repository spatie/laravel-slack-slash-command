<?php

namespace Spatie\SlashCommand;

interface HandlesSlashCommand
{
    public function getSlashCommandData(): SlashCommandData;

    public function respondToSlack(string $text): SlashCommandResponse;
}
