<?php

namespace Albet\Asmvc\Core\Cli;

class Loader
{
    /**
     * Load a command by it's clases.
     */
    public function load()
    {
        return [
            \Albet\Asmvc\Core\Cli\Commands\Setup::class,
            \Albet\Asmvc\Core\Cli\Commands\Serve::class,
            \Albet\Asmvc\Core\Cli\Commands\InstallBootstrap::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateController::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateModel::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateMiddleware::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateMigration::class,
            \Albet\Asmvc\Core\Cli\Commands\RunMigration::class,
            \Albet\Asmvc\Core\Cli\Commands\RollbackMigration::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateSeeder::class,
            \Albet\Asmvc\Core\Cli\Commands\RunSeeder::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateTest::class,
            \Albet\Asmvc\Core\Cli\Commands\RunTest::class,
            \Albet\Asmvc\Core\Cli\Commands\ExportCore::class,
            \Albet\Asmvc\Core\Cli\Commands\ASMVCVersion::class
        ];
    }
}
