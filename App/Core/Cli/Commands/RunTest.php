<?php

namespace App\Asmvc\Core\Cli\Commands;

use App\Asmvc\Core\Cli\Cli;
use App\Asmvc\Core\Exceptions\SystemInvalidException;

class RunTest extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = 'run:test';
    protected $hint = "test?";
    protected $desc = 'Running Test';

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if (PHP_OS_FAMILY == 'windows') {
            $path = 'vendor\\bin\\phpunit';
        } else {
            $path = 'vendor/bin/phpunit';
        }
        if ($try) {
            if (!function_exists('system')) {
                throw new SystemInvalidException();
            }
            system("{$path} --configuration phpunit.xml App/Tests/{$try}.php", $result);
            echo $result;
        } else {
            system("{$path} --configuration phpunit.xml", $result);
            echo $result;
        }
    }
}
