<?php

namespace Spatie\SlashCommand;

use Symfony\Component\Console\Input\StringInput;

trait CanParseSignature
{
    /** @var StringInput */
    protected $input;

    protected function parseSignature()
    {
        list($name, $arguments, $options) = Parser::parse($this->signature);

        $this->name = $name;

        $inputDefinition = new InputDefinition();

        foreach($arguments as $argument) {
            $inputDefinition->addArgument($argument);
        }

        foreach($options as $option) {
            $inputDefinition->addOption($option);
        }

        $this->input = new StringInput($this->slashCommandData->text);
    }
}