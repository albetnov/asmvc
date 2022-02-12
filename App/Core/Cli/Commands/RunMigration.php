<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\EloquentDB;
use Illuminate\Database\Schema\Blueprint;

class RunMigration extends BaseCli
{
    protected $command = "run:migration";
    protected $hint = "MigrationName?,fresh?";
    protected $desc = "Run a migration file or refresh your migration.";

    private function historyCheckup()
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

    private function fillHistory($table)
    {
        $eloquent = new EloquentDB;
        $eloquent::table('migration_history')->insert([
            'migration_name' => $table,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function checkHistoryMigration($table)
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

    public function register()
    {
        $try = $this->next_arguments(1);

        if ($this->next_arguments(2) == 'fresh' || $try == 'fresh') {
            (new EloquentDB)->schema()->dropAllTables();
            echo "Tables Dropped successfully!\n";
            echo "Starting migrating...\n";
        }

        if ($try && $try != 'fresh') {
            if ($this->checkHistoryMigration($try)) {
                echo "Table: {$try} already exist. Skipping...\n";
                exit;
            }
            if (!class_exists($try)) {
                $get = require_once base_path("App/Database/Migrations/{$try}.php");
                $get->up();
                echo "Migrated: {$try}.\n";
            } else {
                $class = "\Albet\Asmvc\Database\Migrations\{$try}";
                (new $class())->up();
            }
            $this->fillHistory($try);
        } else {
            $diffed = array_diff(scandir(base_path() . "/App/Database/Migrations"), ['.', '..', '.gitkeep']);
            $dirtho = [];
            foreach ($diffed as $diffed) {
                if (!str_contains($diffed, '.')) {
                    $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                    $dirtho[] = $diffed;
                    foreach ($dirs as $dir) {
                        if ($this->checkHistoryMigration($dir)) {
                            echo "Table: {$dir} already exist. Skipping...\n";
                        } else {
                            echo "Migrated: {$dir}.\n";
                            if (!class_exists($dir)) {
                                $get = require_once base_path("App/Database/Migrations/{$dir}");
                                $get->up();
                            } else {
                                $class = "\Albet\Asmvc\Database\Migrations\{$dir}";
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
                        if (!class_exists($diffed)) {
                            $get = require_once base_path("App/Database/Migrations/{$diffed}");
                            $get->up();
                        } else {
                            $class = "\Albet\Asmvc\Database\Migrations\{$diffed}";
                            (new $class())->up();
                        }
                        $this->fillHistory($diffed);
                    }
                }
            }
        }
    }
}
