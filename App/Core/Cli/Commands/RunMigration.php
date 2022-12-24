<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\EloquentDB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;

class RunMigration extends BaseCli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "run:migration";
    protected $hint = "fresh?";
    protected $desc = "Run a migration file or refresh your migration.";

    /**
     * Check history table exist.
     * @return Albet\Asmvc\Core\EloquentDB
     */
    private function historyCheckup(): Builder
    {
        $eloquent = new EloquentDB;
        $check = $eloquent->schema()->hasTable('migration_history');
        if (!$check) {
            $eloquent->schema()->create('migration_history', function (Blueprint $table) {
                $table->id();
                $table->string('migration_name');
                $table->timestamp('created_at');
            });
        }
        return $eloquent->table('migration_history');
    }

    /**
     * Register a history
     * @param string $table
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
     * @param string $table
     * @return bool
     */
    private function checkHistoryMigration(string $table): bool
    {
        if ($this->historyCheckup()->count() > 0) {
            $check = $this->historyCheckup()->where('migration_name', $table)->first();
            if (isset($check->migration_name) && $check->migration_name == $table) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Remove extension from a file
     * @param string $file
     * @return string
     */
    private function noExtension(string $file): string
    {
        $result = explode('.', $file);
        array_pop($result);
        return implode('.', $result);
    }

    /**
     * Register the command
     */
    public function register()
    {
        if ($this->next_arguments(1) == 'fresh') {
            (new EloquentDB)->schema()->dropAllTables();
            echo "Tables Dropped successfully!\n";
            echo "Starting migrating...\n";
        }
        $diffed = array_diff(scandir(base_path() . "/App/Database/Migrations"), ['.', '..', '.gitkeep']);
        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    if ($this->checkHistoryMigration($dir)) {
                        echo "Table: {$dir} already exist. Skipping...\n";
                    } else {
                        echo "Migrated: {$dir}.\n";
                        $noext = $this->noExtension($dir);
                        $find_class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
                        if (!class_exists($find_class)) {
                            $get = include base_path("App/Database/Migrations/{$dir}");
                            $get->up();
                        } else {
                            $class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
                            (new $class())->up();
                        }
                        $this->fillHistory($dir);
                    }
                }
            } else {
                if ($this->checkHistoryMigration($diffed)) {
                    echo "Table: {$diffed} already exist. Skipping...\n";
                } else {
                    echo "Migrated: $diffed\n";
                    $noext = $this->noExtension($diffed);
                    $find_class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
                    if (!class_exists($find_class)) {
                        $get = include base_path("App/Database/Migrations/{$diffed}");
                        $get->up();
                    } else {
                        $class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
                        (new $class())->up();
                    }
                    $this->fillHistory($diffed);
                }
            }
        }
    }
}
