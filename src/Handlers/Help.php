<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class Help extends SignatureHandler
{
    protected $signature = '* help {command? : The command you want information about}';

    protected $description = 'List all commands or provide information about all commands';

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $handlers = $this->findAvailableHandlers();

        if ($command = $this->getArgument('command')) {
            return $this->displayHelpForCommand($handlers, $command);
        }

        return $this->displayListOfAllCommands($handlers);
    }

    /**
     * Find all handlers that are available for the current SlashCommand
     * and have a signature.
     *
     * @return Collection|SignatureHandler[]
     */
    protected function findAvailableHandlers(): Collection
    {
        return collect(config('laravel-slack-slash-command.handlers'))
            ->map(function (string $handlerClassName) {
                return new $handlerClassName($this->request);
            })
            ->filter(function (HandlesSlashCommand $handler) {
                return $handler instanceof SignatureHandler;
            })
            ->filter(function (SignatureHandler $handler) {
                $signatureParts = new SignatureParts($handler->getSignature());

                return Str::is($signatureParts->getSlashCommandName(), $this->request->command);
            });
    }

    /**
     * Show the help information for a single SignatureHandler.
     *
     * @param  Collection|SignatureHandler[] $handlers
     * @param  string $command
     * @return Response
     */
    protected function displayHelpForCommand(Collection $handlers, string $command): Response
    {
        $helpRequest = clone $this->request;

        $helpRequest->text = $command;

        /** @var \Spatie\SlashCommand\Handlers $handler */
        $handler = $handlers->filter(function (HandlesSlashCommand $handler) use ($helpRequest) {
            return $handler->canHandle($helpRequest);
        })
            ->first();

        $field = AttachmentField::create($handler->getFullCommand(), $handler->getHelpDescription());

        return $this->respondToSlack('')
            ->withAttachment(
                Attachment::create()->addField($field)
            );
    }

    /**
     * Show a list of all available handlers.
     *
     * @param  Collection|SignatureHandler[] $handlers
     * @return Response
     */
    protected function displayListOfAllCommands(Collection $handlers): Response
    {
        $attachmentFields = $handlers
            ->sort(function(SignatureHandler $handlerA, SignatureHandler $handlerB) {
                return strcmp($handlerA->getFullCommand(), $handlerB->getFullCommand());
            })
            ->map(function (SignatureHandler $handler) {
                return AttachmentField::create($handler->getFullCommand(), $handler->getDescription());
            })
            ->all();

        return $this->respondToSlack('Available commands:')
            ->withAttachment(
                Attachment::create()
                    ->setColor('good')
                    ->setFields($attachmentFields)
            );
    }
}
