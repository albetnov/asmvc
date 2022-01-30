<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class ASMVCVersion extends BaseCli
{
    protected $command = "version";
    protected $desc = "Show ASMVC Version";

    public function register()
    {
        echo 'ASMVC Version ' . ASMVC_VERSION . ' ' . ASMVC_STATE . PHP_EOL;
    }
}
