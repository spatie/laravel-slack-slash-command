<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class CatchAll extends BaseHandler
{
    /**
     * If this function returns true, the handle method will get called.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        return true;
    }

    /**
     * Handle the given request. Remember that Slack expects a response
     * within three seconds after the slash command was issued. If
     * there is more time needed, dispatch a job.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $response = $this->respondToSlack("I did not recognize this command: `/{$request->command} {$request->text}`");

        list($command) = explode(' ', $this->request->text);

        $alternativeHandlers = $this->findAlternativeHandlers($command);

        if ($alternativeHandlers->count()) {
            $response->withAttachment($this->getCommandListAttachment($alternativeHandlers));
        }

        if ($this->containsHelpHandler($alternativeHandlers)) {
            $response->withAttachment(Attachment::create()
                ->setText("For all available commands, try `/{$request->command} help`")
            );
        }

        return $response;
    }

    protected function findAlternativeHandlers(string $command): Collection
    {
        $alternativeHandlers = collect(config('laravel-slack-slash-command.handlers'))
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

        if (strpos($command, ':') !== false) {
            $subHandlers = $this->findInNamespace($alternativeHandlers, $command);
            if ($subHandlers->count()) {
                return $subHandlers;
            }
        }

        return $alternativeHandlers->filter(function (SignatureHandler $handler) use ($command) {
            return levenshtein($handler->getName(), $command) <= 2;
        });
    }

    protected function findInNamespace(Collection $handlers, string $command): Collection
    {
        // Find commands in the same namespace
        list($namespace, $subCommand) = explode(':', $command);

        $subHandlers = $handlers->filter(function (SignatureHandler $handler) use ($namespace) {
            return Str::startsWith($handler->getName(), $namespace.':');
        });

        return $subHandlers;
    }

    protected function getCommandListAttachment(Collection $handlers): Attachment
    {
        $attachmentFields = $handlers
            ->map(function (SignatureHandler $handler) {
                return AttachmentField::create($handler->getFullCommand(), $handler->getDescription());
            })
            ->all();

        return Attachment::create()
            ->setColor('warning')
            ->setTitle('Did you mean:')
            ->setFields($attachmentFields);
    }

    protected function containsHelpHandler(Collection $alternativeHandlers): bool
    {
        return ! $alternativeHandlers->filter(function (SignatureHandler $handler) {
            return $handler instanceof Help;
        })->isEmpty();
    }
}
