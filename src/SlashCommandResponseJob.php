<?php

namespace Spatie\SlashCommand;

use Illuminate\Contracts\Queue\ShouldQueue;

abstract class SlashCommandResponseJob implements ShouldQueue, HandlesSlashCommand
{
    /** @var \Spatie\SlashCommand\SlashCommandData */
    public $slashCommandData;

    public function __construct(SlashCommandData $slashCommandData)
    {
        $this->slashCommandData = $slashCommandData;
    }
    
    public function getSlashCommandResponse(): SlashCommandResponse
    {
        return SlashCommandResponse::create($this->slashCommandData);
    }

    public function respondToSlack(string $text): SlashCommandResponse
    {
        return $this->getSlashCommandResponse()->setText($text);
    }

    abstract function handle();

}
