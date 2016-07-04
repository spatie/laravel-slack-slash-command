<?php

namespace Spatie\LaravelSlack;

use Illuminate\Http\Response;

class SlashCommandResponse extends Response
{
    /** @var SlashCommandRequest */
    protected $request;

    /** @var string */
    protected $type = 'ephemeral';

    public function createForRequest(SlashCommandRequest $request)
    {
        return (new static())
            ->displayResponseOnlyToUserWhoTypedCommand()
            ->inChannel($request->getChannelName());
    }

    public function displayResponseOnlyToUserWhoTypedCommand()
    {
        $this->type = 'ephemeral';

        return $this;
    }

    public function displayResponseToEveryoneOnChannel()
    {
        $this->type = 'in_channel';

        return $this;
    }
}