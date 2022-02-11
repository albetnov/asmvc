<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\EloquentDB;

class RunMigration extends BaseCli
{
    protected $command = "run:migration";
    protected $hint = "MigrationName?,fresh?";
    protected $desc = "Run a migration file or refresh your migration.";

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($this->next_arguments(2) == 'fresh' || $try == 'fresh') {
            (new EloquentDB)->schema()->dropAllTables();
            echo "Tables Dropped successfully...\n";
            echo "Starting migrating\n";
        }
        if ($try && $try != 'fresh') {
            if (!class_exists($try)) {
                $get = require_once base_path("App/Database/Migrations/{$try}.php");
                $get->up();
                echo "Migrated: {$try}.\n";
            } else {
                $class = "\Albet\Asmvc\Database\Migrations\{$try}";
                (new $class())->up();
            }
        } else {
            $diffed = array_diff(scandir(base_path() . "/App/Database/Migrations"), ['.', '..', '.gitkeep']);
            $dirtho = [];
            foreach ($diffed as $diffed) {
                if (!str_contains($diffed, '.')) {
                    $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                    $dirtho[] = $diffed;
                    foreach ($dirs as $dir) {
                        echo "Migrated: {$dir}.\n";
                        if (!class_exists($dir)) {
                            $get = require_once base_path("App/Database/Migrations/{$dir}");
                            $get->up();
                        } else {
                            $class = "\Albet\Asmvc\Database\Migrations\{$dir}";
                            (new $class())->up();
                        }
                    }
                } else {
                    echo "Migrated: $diffed\n";
                    if (!class_exists($diffed)) {
                        $get = require_once base_path("App/Database/Migrations/{$diffed}");
                        $get->up();
                    } else {
                        $class = "\Albet\Asmvc\Database\Migrations\{$diffed}";
                        (new $class())->up();
                    }
                }
            }
        }
    }
}
