<?php

namespace App\Asmvc\Core\Cli;

class Loader
{
    /**
     * Load a command by it's clases.
     */
    public function load(): array
    {
        return [
            \App\Asmvc\Core\Cli\Commands\Setup::class,
            \App\Asmvc\Core\Cli\Commands\Serve::class,
            \App\Asmvc\Core\Cli\Commands\InstallBootstrap::class,
            \App\Asmvc\Core\Cli\Commands\CreateController::class,
            \App\Asmvc\Core\Cli\Commands\CreateModel::class,
            \App\Asmvc\Core\Cli\Commands\CreateMiddleware::class,
            \App\Asmvc\Core\Cli\Commands\CreateMigration::class,
            \App\Asmvc\Core\Cli\Commands\RunMigration::class,
            \App\Asmvc\Core\Cli\Commands\RollbackMigration::class,
            \App\Asmvc\Core\Cli\Commands\CreateSeeder::class,
            \App\Asmvc\Core\Cli\Commands\RunSeeder::class,
            \App\Asmvc\Core\Cli\Commands\CreateTest::class,
            \App\Asmvc\Core\Cli\Commands\RunTest::class,
            \App\Asmvc\Core\Cli\Commands\ExportCore::class,
            \App\Asmvc\Core\Cli\Commands\ASMVCVersion::class
        ];
    }
}
