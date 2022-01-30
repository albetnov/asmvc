<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\Cli\ResetRouter as CliResetRouter;

class ResetRouter extends BaseCli
{
    protected $command = "reset:router";
    protected $desc = "Switching route file with the fresh install state";

    use CliResetRouter;

    public function register()
    {
        $this->resetRouter();
    }
}
