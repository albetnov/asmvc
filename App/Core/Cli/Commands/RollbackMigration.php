<?php

namespace App\Asmvc\Core\Cli\Commands;

use App\Asmvc\Core\Cli\Cli;
use App\Asmvc\Core\Eloquent\EloquentDB;

class RollbackMigration extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "migration:rollback";
    protected $hint = "clean?";
    protected $desc = "Rollback your migration.";

    /**
     * Clear history table
     * @param string $table
     */
    private function clearHistory(?string $table = null)
    {
        $eloquent = new EloquentDB;
        $check = $eloquent->schema()->hasTable('migration_history');
        if (!$check) {
            echo "No history found skipping...\n";
            return;
        }
        if ($table) {
            $check = $eloquent->table('migration_history')->where('migration_name', $table);
            $field = $check->first();
            if (isset($field->migration_name) && $field->migration_name == $table) {
                $check->delete();
            }
        } else {
            $eloquent->schema()->dropIfExists('migration_history');
        }
    }

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1) != "clean" ? $this->next_arguments(1) : true;
        $next = $try ? true : $this->next_arguments(2);

        $path = base_path() . "/App/Database/Migrations";
        $diffed = array_diff(scandir($path), ['.', '..', '.gitkeep']);
        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    echo "Rollback: {$dir}.\n";
                    $get = require_once base_path("App/Database/Migrations/{$dir}");
                    $get->down();
                }
            } else {
                echo "Rollback: $diffed\n";
                $get = require_once base_path("App/Database/Migrations/{$diffed}");
                $get->down();
            }
            if ($next) {
                $this->clearHistory();
            }
        }
    }
}
