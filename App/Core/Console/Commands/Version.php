<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;

class Version extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder
            ->setName('version')
            ->setAliases('ver')
            ->setDesc('Show framework version');
    }

    public function handler($input, $output): int
    {
        $output->writeln('ASMVC Version ' . ASMVC_VERSION . ' ' . ASMVC_STATE);
        return Command::SUCCESS;
    }
}
