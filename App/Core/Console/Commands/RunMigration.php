<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentOptionalParamBuilder;
use App\Asmvc\Core\Eloquent\EloquentDB;
use App\Asmvc\Database\Sorter;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunMigration extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName("run:migration")
            ->setAliases("migrate")
            ->setDesc("Migrate your database")
            ->addOptionalParam(
                fn (FluentOptionalParamBuilder $opb) => $opb->setName('fresh')
                    ->setInputTypeNone()
                    ->setDesc('Refresh your migration')
                    ->setShortcut('f')
            )->addOptionalParam(
                fn (FluentOptionalParamBuilder $opb) => $opb->setName('no-sort')
                    ->setDesc('Ignore Sorter.php')
                    ->setInputTypeNone()
                    ->setShortcut('ns')
            );
    }

    /**
     * Check history table exist.
     * @return App\Asmvc\Core\EloquentDB
     */
    private function historyCheckup(): Builder
    {
        $eloquent = new EloquentDB;
        $check = $eloquent->schema()->hasTable('migration_history');
        if (!$check) {
            $eloquent->schema()->create('migration_history', function (Blueprint $table): void {
                $table->id();
                $table->string('migration_name');
                $table->timestamp('created_at');
            });
        }
        return $eloquent->table('migration_history');
    }

    /**
     * Register a history
     */
    private function fillHistory(string $table): void
    {
        $eloquent = new EloquentDB;
        $eloquent::table('migration_history')->insert([
            'migration_name' => $table,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if table exist in migration.
     */
    private function checkHistoryMigration(string $table): bool
    {
        if ($this->historyCheckup()->count() > 0) {
            $check = $this->historyCheckup()->where('migration_name', $table)->first();
            return isset($check->migration_name) && $check->migration_name == $table;
        }
        return false;
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        if ($inputInterface->getOption('fresh')) {
            (new EloquentDB)->schema()->dropAllTables();
            $this->badgeSuccess("Tables dropped successfully.");
        }

        $this->badgeInfo("Starting migration...");

        $diffed = array_diff(scandir(base_path() . "/App/Database/Migrations"), ['.', '..', '.gitkeep']);

        $sorter = new Sorter();

        if ($sorter->migrations() && !$inputInterface->getOption('no-sort')) {
            $diffed = collect($sorter->migrations())->map(fn ($item) => "{$item}.php");
        }

        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    if ($this->checkHistoryMigration($dir)) {
                        $this->badgeWarn("Table: {$dir} already exist. Skipping...");
                    } else {
                        $this->badgeSuccess("Migrated: {$dir}.");
                        $get = include base_path("App/Database/Migrations/{$dir}");
                        $get->up();
                        $this->fillHistory($dir);
                    }
                }
            } elseif ($this->checkHistoryMigration($diffed)) {
                $this->badgeWarn("Table: {$diffed} already exist. Skipping...");
            } else {
                $this->badgeSuccess("Migrated: $diffed");
                $get = include base_path("App/Database/Migrations/{$diffed}");
                $get->up();
                $this->fillHistory($diffed);
            }
        }

        $this->success("Migration finished!");

        return Command::SUCCESS;
    }
}
