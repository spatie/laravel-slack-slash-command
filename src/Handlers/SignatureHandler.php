<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Console\Parser;
use Illuminate\Support\Str;
use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Exceptions\InvalidInput;
use Spatie\SlashCommand\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class SignatureHandler extends BaseHandler
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $signature;

    /** @var string */
    protected $description;

    /** @var \Symfony\Component\Console\Input\StringInput */
    protected $input;

    /** @var \Symfony\Component\Console\Input\InputDefinition */
    protected $inputDefinition;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getDescription(): string
    {
        return $this->description ?: '';
    }

    /**
     * @param string $foo
     */
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

    /**
     * Get the full command (eg. `/bot ping`).
     *
     * @return string
     */
    public function getFullCommand(): string
    {
        return "/{$this->request->command} {$this->name}";
    }

    /**
     * Get the usage description, including parameters and options.
     *
     * @return string
     */
    public function getHelpDescription(): string
    {
        $inputDefinition = $this->inputDefinition;
        $output = new BufferedOutput();

        $name = $this->getFullCommand();

        $command = (new Command($name))
            ->setDefinition($inputDefinition)
            ->setDescription($this->getDescription());

        $descriptor = new DescriptorHelper();
        $descriptor->describe($output, $command);

        return $output->fetch();
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

        [$name, $arguments, $options] = Parser::parse($signature);

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
        $this->inputDefinition = $inputDefinition;

        try {
            $this->input->bind($inputDefinition);
        } catch (RuntimeException $exception) {
            return false;
        }

        return true;
    }

    public function validate()
    {
        try {
            $this->input->validate();
        } catch (RuntimeException $exception) {
            throw new InvalidInput($exception->getMessage(), $this, $exception);
        }
    }
}
