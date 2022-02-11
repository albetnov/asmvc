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
        if (PHP_OS_FAMILY == 'windows') {
            $path = 'vendor\\bin\\phpunit';
        } else {
            $path = 'vendor/bin/phpunit';
        }
        if ($try) {
            system("{$path} --configuration phpunit.xml App/Tests/{$try}.php", $result);
            echo $result;
        } else {
            system("{$path} --configuration phpunit.xml", $result);
            echo $result;
        }
    }
}
