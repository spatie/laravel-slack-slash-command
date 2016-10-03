<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Support\Str;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class Help extends BaseHandler
{

    /**
     * Check if the command begins with 'help'
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return bool
     */
    public function canHandle(Request $request): bool
    {
        return Str::startsWith($request->text, 'help');
    }

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $command = trim(substr($this->request->text, 4));
        $helpRequest = clone $this->request;
        $helpRequest->text = $command;

        $handlers = collect(config('laravel-slack-slash-command.handlers'))
            ->map(function (string $handlerClassName) use($helpRequest) {
                return new $handlerClassName($helpRequest);
            })
            ->filter(function (HandlesSlashCommand $handler) use ($helpRequest){
                if ($handler instanceof SignatureHandler && isset($handler->signature)) {
                    $signatureParts = new SignatureParts($handler->signature);
                    return in_array($signatureParts->getSlashCommandName(), [$this->request->command, '*']);
                }
            });

        // When command is passed, find all commands
        if (! empty($command)) {

            /** @var SignatureHandler $handler */
            $handler = $handlers
                ->filter(function (HandlesSlashCommand $handler) use ($helpRequest){
                    return $handler->canHandle($helpRequest);
                })
                ->first();

            $signature = $this->formatSignature($handler->signature);

            return $this->respondToSlack("Usage for command */{$this->request->command} {$command}*")
                ->withAttachment(Attachment::create()->setText($signature));
        } else {
            // Create AttachmentFields for each handler
            $attachmentFields = collect($handlers)->reduce(function (array $attachmentFields, SignatureHandler $handler) {

                $signature = $this->formatSignature($handler->signature);
                $signatureParts = new SignatureParts($signature);
                $attachmentFields[] = AttachmentField::create($signatureParts->getHandlerName(), $signature);

                return $attachmentFields;
            }, []);

            return $this->respondToSlack("Listing all commands available for */{$this->request->command}*:")
                ->withAttachment(Attachment::create()
                    ->setFields($attachmentFields)
                );
        }
    }

    protected function formatSignature($signature)
    {
        $signatureParts = new SignatureParts($signature);
        return '/' . $this->request->command . ' ' . $signatureParts->getSignatureWithoutCommandName();
    }
}
