<?php

namespace Albet\Asmvc\Core\Cli;

class Loader
{
    public function load()
    {
        return [
            \Albet\Asmvc\Core\Cli\Commands\Serve::class,
            \Albet\Asmvc\Core\Cli\Commands\InstallBootstrap::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateController::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateModel::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateMiddleware::class,
            \Albet\Asmvc\Core\Cli\Commands\CreateTest::class,
            \Albet\Asmvc\Core\Cli\Commands\RunTest::class,
            \Albet\Asmvc\Core\Cli\Commands\ResetRouter::class,
            \Albet\Asmvc\Core\Cli\Commands\ExportCore::class,
            \Albet\Asmvc\Core\Cli\Commands\Cleanup::class,
            \Albet\Asmvc\Core\Cli\Commands\ASMVCVersion::class
        ];
    }
}
