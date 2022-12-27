<?php

namespace App\Asmvc\Core\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\render;

abstract class Command extends SymfonyCommand
{
    protected string $name = "";
    protected array $aliases = [];
    protected string $desc = "";

    protected function render(string $html)
    {
        return render($html);
    }

    public function __call($method, $parameters)
    {
        if ($method === "parse") return $this->parse();
    }

    abstract function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int;
    abstract protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder;

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        return $this->handler($input, $output);
    }

    private function parse(): self
    {
        $builder = $this->setup(new FluentCommandBuilder)->parse();

        $this->setName($builder->name);
        $this->setDescription($builder->desc);
        $this->setAliases($builder->aliases);
        if ($builder->help) {
            $this->setHelp($builder->help);
        }

        foreach ($builder->params as $key => $value) {
            $this->addArgument($key, $value['type'], $value['desc'], $value['default']);
        }

        foreach ($builder->optionalParams as $key => $value) {
            $this->addOption($key, $value['shortcut'], $value['type'], $value['desc'], $value['default']);
        }

        return $this;
    }
}
