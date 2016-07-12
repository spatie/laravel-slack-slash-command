<?php

namespace Spatie\SlashCommand\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\SlashCommandData;
use Spatie\SlashCommand\SlashCommandResponse;

 abstract class SlashCommandResponseJob implements ShouldQueue, HandlesSlashCommand
{
    /** @var \Spatie\SlashCommand\SlashCommandData */
    public $slashCommandData;

    public function getSlashCommandResponse(): SlashCommandResponse
    {
        return SlashCommandResponse::create($this->slashCommandData);
    }
     
     public function setSlashCommandResponse(SlashCommandData $slashCommandData)
     {
         $this->slashCommandData = $slashCommandData;

         return $this;
     }

    public function respondToSlack(string $text): SlashCommandResponse
    {
        return $this->getSlashCommandResponse()->setText($text);
    }

     public function getSlashCommandData(): SlashCommandData
     {
         return $this->slashCommandData;
     }

    abstract public function handle();
 }
