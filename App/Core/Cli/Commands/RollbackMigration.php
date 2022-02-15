<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\EloquentDB;

class RollbackMigration extends BaseCli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "migration:rollback";
    protected $hint = "MigrationName?,clean?";
    protected $desc = "Rollback your migration.";

    /**
     * Clear history table
     * @param string $table
     */
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

    /**
     * Remove extension from a file name
     * @param string $file
     * @return string
     */
    private function noExtension($file)
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
        $try = $this->next_arguments(1) != "clean" ? $this->next_arguments(1) : true;
        $next = $try ? true : $this->next_arguments(2);

        if ($try && $try != "clean") {
            if (!class_exists($this->noExtension($try))) {
                $get = require_once base_path("App/Database/Migrations/{$try}.php");
                $get->down();
                echo "Rollback: {$try}.\n";
            } else {
                $class = "\\Albet\\Asmvc\Database\\Migrations\\{$try}";
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
                        $noext = $this->noExtension($dir);
                        if (!class_exists($noext)) {
                            $get = require_once base_path("App/Database/Migrations/{$dir}");
                            $get->down();
                        } else {
                            $class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
                            (new $class())->down();
                        }
                    }
                } else {
                    echo "Rollback: $diffed\n";
                    $noext = $this->noExtension($diffed);
                    if (!class_exists($noext)) {
                        $get = require_once base_path("App/Database/Migrations/{$diffed}");
                        $get->down();
                    } else {
                        $class = "\\Albet\\Asmvc\\Database\\Migrations\\{$noext}";
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
