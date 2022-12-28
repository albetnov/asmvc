<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Contracts\BadgeColor;
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
        $ver = ASMVC_VERSION;
        $state = ASMVC_STATE;
        $this->info("ASMVC Version $ver : $state");
        return Command::SUCCESS;
    }
}
