<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Console\Parser;
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
    protected $couldBindSignature = false;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if (empty($this->signature)) {
            throw InvalidHandler::signatureIsRequired(static::class);
        }

        $this->parseSignature();
    }

    protected function parseSignature()
    {
        list($name, $arguments, $options) = Parser::parse($this->signature);

        $this->name = $name;

        $inputDefinition = new InputDefinition();

        foreach ($arguments as $argument) {
            $inputDefinition->addArgument($argument);
        }

        foreach ($options as $option) {
            $inputDefinition->addOption($option);
        }

        $this->input = new StringInput($this->request->text);

        try {
            $this->input->bind($inputDefinition);
            $this->couldBindSignature = true;
        } catch (RuntimeException $exception) {
        }
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
        if (!$this->couldBindSignature) {
            return false;
        }

        $commandName = explode(' ', $this->signature)[0];

        return strtolower($request->command) === strtolower($commandName);
    }
}
