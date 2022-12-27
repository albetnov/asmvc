<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;

class Version extends Command
{
    protected string $name = 'version';
    protected string $desc = "Show Framework Version";

    public function handler($input, $output): int
    {
        $output->writeln("Test Symfony Console");
        return Command::SUCCESS;
    }
}
