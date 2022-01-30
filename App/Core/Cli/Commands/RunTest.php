<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class RunTest extends BaseCli
{
    protected $command = 'run:test';
    protected $hint = "test?";
    protected $desc = 'Running Test';

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            system('vendor\bin\phpunit --configuration phpunit.xml App/Tests/' . $try . '.php', $result);
            echo $result;
        } else {
            system('vendor\bin\phpunit --configuration phpunit.xml', $result);
            echo $result;
        }
    }
}
