<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentParamBuilder;

class Version extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder
            ->setName('version')
            ->setAliases('ver')
            ->setDesc('Show Framework Version')
            ->setHelp('Help mee')
            ->addParam(
                fn (FluentParamBuilder $args) => $args
                    ->setName('world')
                    ->setDesc("hello but world")
            )
            ->addParam(
                fn (FluentParamBuilder $args) => $args
                    ->setName('hello')
                    ->setDesc('hello world')
                    ->setInputTypeOptional()
                    ->setDefault('default value')
            );
    }

    public function handler($input, $output): int
    {
        echo $input->getArgument('hello');
        echo $input->getArgument('world');
        $output->writeln("Test Symfony Console");
        return Command::SUCCESS;
    }
}
