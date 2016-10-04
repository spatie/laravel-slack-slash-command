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
    protected $helpAvailable = false;

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

        list($command) = explode(' ' , $this->request->text);

        $alternatives = $this->findAlternatives($command);
        if (! $alternatives->isEmpty()) {
            $response->withAttachment($this->getCommandListAttachment($alternatives));
        }

        if ($this->helpAvailable) {
            $response->withAttachment(Attachment::create()
                ->setText("For all available commands, try `/{$request->command} help`")
            );
        }
        return $response;
    }

    protected function findAlternatives(string $command): Collection
    {
        // Number of characters to change
        $threshold = 2;

        $handlers = collect(config('laravel-slack-slash-command.handlers'))
            ->map(function (string $handlerClassName) {
                return new $handlerClassName($this->request);
            })
            ->filter(function (HandlesSlashCommand $handler) {
                return $handler instanceof SignatureHandler;
            })
            ->filter(function (SignatureHandler $handler) {
                $signatureParts = new SignatureParts($handler->getSignature());
                return Str::is($signatureParts->getSlashCommandName(), $this->request->command);
            })
            ->map(function(SignatureHandler $handler){
                if ($handler instanceof Help) {
                    $this->helpAvailable = true;
                }
                return $handler;
            })
            ;

        if (strpos($command, ':') !== false) {
            $subHandlers = $this->findInNamespace($handlers, $command);
            if (! $subHandlers->isEmpty()) {
                return $subHandlers;
            }
        }

        return $handlers->filter(function(SignatureHandler $handler) use($command, $threshold) {
            return levenshtein($handler->getName(), $command) <= $threshold;
        });
    }

    protected function findInNamespace(Collection $handlers, string $command): Collection
    {
        // Find commands in the same namespace
        list($namespace, $subCommand) = explode(':', $command);

        $subHandlers = $handlers->filter(function (SignatureHandler $handler) use($namespace) {
            return Str::startsWith($handler->getName(), $namespace . ':' );
        });

        return $subHandlers;
    }

    protected function getCommandListAttachment(Collection $handlers): Attachment
    {
        $attachmentFields = $handlers->map(function (SignatureHandler $handler) {
            return AttachmentField::create($handler->getFullCommand(), $handler->getDescription());
        })
            ->all();

        return Attachment::create()
            ->setTitle('Did you mean:')
            ->setFields($attachmentFields);
    }
}
