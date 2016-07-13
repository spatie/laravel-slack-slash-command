<?php

namespace Spatie\SlashCommand\Handlers;

use Illuminate\Console\Parser;
use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Request;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;

abstract class SignatureHandler extends BaseHandler
{
    /** @var string */
    protected $name;

    /** @var \Symfony\Component\Console\Input\StringInput */
    protected $input;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if (empty($this->signature)) {
            throw InvalidHandler::noSignatureSet(static::class);
        }

        $this->parseSignature();
    }

    protected function parseInput()
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

        $this->input->bind($inputDefinition);
    }

    protected function getArgument($foo)
    {
        return $this->input->getArgument($foo);
    }

    protected function getOption($foo)
    {
        return $this->input->getOption($foo);
    }
}
