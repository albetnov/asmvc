<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Contracts\BadgeColor;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentOptionalParamBuilder;
use App\Asmvc\Core\Eloquent\EloquentDB;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackMigration extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName('rollback:migration')
            ->setAliases('migrate:rollback')
            ->setDesc('Rollback your migration')
            ->addOptionalParam(
                fn (FluentOptionalParamBuilder $opb) => $opb->setName("clear")
                    ->setDesc("Clean Migration History Table")
                    ->setInputTypeNone()
                    ->setShortcut('c')
            );
    }

    /**
     * Clear history table
     * @param string $table
     */
    private function clearHistory(): void
    {
        $eloquent = new EloquentDB;
        $check = $eloquent->schema()->hasTable('migration_history');
        if (!$check) {
            $this->badgeWarn("No history found skipping...");
            return;
        }
        $eloquent->schema()->dropIfExists('migration_history');
        $this->badgeSuccess("History Cleared!");
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $path = base_path() . "/App/Database/Migrations";
        $diffed = array_diff(scandir($path), ['.', '..', '.gitkeep']);
        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    $this->badgeSuccess("Rollback: {$dir}.");
                    $get = require_once base_path("App/Database/Migrations/{$dir}");
                    $get->down();
                }
            } else {
                $this->badgeSuccess("Rollback: $diffed");
                $get = require_once base_path("App/Database/Migrations/{$diffed}");
                $get->down();
            }
        }

        if ($inputInterface->hasOption('clear')) {
            $this->clearHistory();
        }

        $this->success("Rollback finish.");

        return Command::SUCCESS;
    }
}
