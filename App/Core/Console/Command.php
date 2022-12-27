<?php

namespace App\Asmvc\Core\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected string $name = "";
    protected array $aliases = [];
    protected string $desc = "";
    protected array $args = [];

    public function __call($method, $parameters)
    {
        if ($method === "parse") return $this->parse();
    }

    abstract function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->handler($input, $output);
    }

    private function parse(): self
    {
        if (trim($this->name) === "") {
            throw new InvalidCommandNameException();
        }

        if (trim($this->desc) === "") {
            throw new InvalidCommandDescException();
        }

        if (!isAssociativeArray($this->args)) {
            throw new InvalidCommandArgumentException();
        }

        $this->setName($this->name);
        $this->setDescription($this->desc);
        $this->setAliases($this->aliases);

        foreach ($this->args as $key => $value) {
            /**
             * @TODO
             * Determine whenever the string contains "--" mark it as optional.
             */
            $inputType = InputArgument::REQUIRED;

            if (str_starts_with($key, "--")) {
                $inputType = InputArgument::OPTIONAL;
                $key = explode("--", $key)[1];
            }


            if (isAssociativeArray($value)) {
                $this->addArgument($key, $inputType, $value['desc'], $value['default']);
            } else {
                $this->addArgument($key, $inputType, $value);
            }
        }

        return $this;
    }
}
