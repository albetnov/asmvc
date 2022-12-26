<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\Cli;

class ASMVCVersion extends Cli
{
    /**
     * @var string $command
     * @var string $desc
     */
    protected $command = "version";
    protected $desc = "Show ASMVC Version";

    /**
     * Register the command
     */
    public function register()
    {
        echo 'ASMVC Version ' . ASMVC_VERSION . ' ' . ASMVC_STATE . PHP_EOL;
    }
}
