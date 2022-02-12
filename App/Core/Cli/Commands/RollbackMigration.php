<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\EloquentDB;

class RollbackMigration extends BaseCli
{
    protected $command = "migration:rollback";
    protected $hint = "MigrationName?,clean?";
    protected $desc = "Rollback your migration.";

    private function clearHistory($table = null)
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

    public function register()
    {
        $try = $this->next_arguments(1) != "clean" ? $this->next_arguments(1) : true;
        $next = $try ? true : $this->next_arguments(2);

        if ($try && $try != "clean") {
            if (!class_exists($try)) {
                $get = require_once base_path("App/Database/Migrations/{$try}.php");
                $get->down();
                echo "Rollback: {$try}.\n";
            } else {
                $class = "\Albet\Asmvc\Database\Migrations\{$try}";
                (new $class())->down();
            }
            if ($next) {
                $this->clearHistory($try . '.php');
            }
        } else {
            $diffed = array_diff(scandir(base_path() . "/App/Database/Migrations"), ['.', '..', '.gitkeep']);
            $dirtho = [];
            foreach ($diffed as $diffed) {
                if (!str_contains($diffed, '.')) {
                    $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                    $dirtho[] = $diffed;
                    foreach ($dirs as $dir) {
                        echo "Rollback: {$dir}.\n";
                        if (!class_exists($dir)) {
                            $get = require_once base_path("App/Database/Migrations/{$dir}");
                            $get->down();
                        } else {
                            $class = "\Albet\Asmvc\Database\Migrations\{$dir}";
                            (new $class())->down();
                        }
                    }
                } else {
                    echo "Rollback: $diffed\n";
                    if (!class_exists($diffed)) {
                        $get = require_once base_path("App/Database/Migrations/{$diffed}");
                        $get->down();
                    } else {
                        $class = "\Albet\Asmvc\Database\Migrations\{$diffed}";
                        (new $class())->down();
                    }
                }
            }
            if ($next) {
                $this->clearHistory();
            }
        }
    }
}
