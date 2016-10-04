<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Console\Parser;
use Illuminate\Support\Str;
use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Request;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;

abstract class SignatureHandler extends BaseHandler
{
    /** @var string */
    protected $name;

    /** @var \Symfony\Component\Console\Input\StringInput */
    protected $input;

    /** @var bool */
    protected $signatureIsBound;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if (empty($this->signature)) {
            throw InvalidHandler::signatureIsRequired(static::class);
        }

        $this->signatureIsBound = $this->bindSignature($this->signature);
    }

    public function getArgument($foo)
    {
        return $this->input->getArgument($foo);
    }

    public function getOption($foo)
    {
        return $this->input->getOption($foo);
    }

    public function getArguments(): array
    {
        return $this->input->getArguments();
    }

    public function getOptions(): array
    {
        return $this->input->getOptions();
    }

    public function canHandle(Request $request): bool
    {
        if (! $this->signatureIsBound) {
            return false;
        }

        $signatureParts = new SignatureParts($this->signature);


        if (! Str::is($signatureParts->getSlashCommandName(), $request->command)) {
            return false;
        }

        if (explode(' ', $request->text)[0] != $signatureParts->getHandlerName()) {
            return false;
        }

        return true;
    }

    protected function bindSignature()
    {
        $signatureParts = new SignatureParts($this->signature);

        $signature = $signatureParts->getSignatureWithoutCommandName();

        list($name, $arguments, $options) = Parser::parse($signature);

        $this->name = $name;

        $inputDefinition = new InputDefinition();

        foreach ($arguments as $argument) {
            $inputDefinition->addArgument($argument);
        }

        foreach ($options as $option) {
            $inputDefinition->addOption($option);
        }

        $inputWithoutHandlerName = explode(' ', $this->request->text, 2)[1] ?? '';

        $this->input = new StringInput($inputWithoutHandlerName);

        try {
            $this->input->bind($inputDefinition);
        } catch (RuntimeException $exception) {
            return false;
        }

        return true;
    }
    
        /**
     * @return Response|null
     */
    protected function validate()
    {
        try {
            $this->input->validate();
        } catch (RuntimeException $e) {
            return $this->respondToSlack('')->withAttachment(
                Attachment::create()
                    ->setColor('danger')
                    ->setText($e->getMessage())
                )
                ->withAttachment(
                    Attachment::create()
                    ->setText($this->getHelpDescription())
                );
        }
    }
}
