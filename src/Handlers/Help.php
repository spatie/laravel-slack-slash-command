<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Support\Str;
use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\HandlesSlashCommand;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Output\BufferedOutput;

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
        $command = $this->getArgument('command');

        $helpRequest = clone $this->request;
        $helpRequest->text = $command;

        $handlers = collect(config('laravel-slack-slash-command.handlers'))
            ->map(function (string $handlerClassName) use($helpRequest) {
                return new $handlerClassName($helpRequest);
            })
            ->filter(function (HandlesSlashCommand $handler) use ($helpRequest){
                if ($handler instanceof SignatureHandler) {
                    $signatureParts = new SignatureParts($handler->getSignature());
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

            return $this->respondToSlack('')
                ->withAttachment(Attachment::create()
                    ->addField($this->getAttachmentFieldForHandler($handler))
                );
        } else {
            // Create AttachmentFields for each handler
            $attachmentFields = collect($handlers)->reduce(function (array $attachmentFields, SignatureHandler $handler) {

                $attachmentFields[] = AttachmentField::create($this->getFullCommand($handler), $handler->getDescription());

                return $attachmentFields;
            }, []);

            return $this->respondToSlack("Available commands:")
                ->withAttachment(Attachment::create()
                    ->setFields($attachmentFields)
                );
        }
    }

    protected function getFullCommand(SignatureHandler $handler): string
    {
        $signatureParts = new SignatureParts($handler->signature);

        return '/' . $this->request->command . ' ' . $signatureParts->getHandlerName();
    }

    protected function getAttachmentFieldForHandler(SignatureHandler $handler): AttachmentField
    {
        $fullCommand = $this->getFullCommand($handler);

        $inputDefinition = $handler->getInputDefinition();
        $output = new BufferedOutput();

        $command = (new Command($fullCommand))
            ->setDefinition($inputDefinition)
            ->setDescription($handler->getDescription())
        ;

        $descriptor = new DescriptorHelper();
        $descriptor->describe($output, $command);

        return AttachmentField::create($fullCommand, $output->fetch());
    }
}
