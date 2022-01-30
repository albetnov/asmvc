<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\Cli\Cleanup;
use Albet\Asmvc\Core\Cli\Loader;
use Albet\Asmvc\Core\Cli\ResetIndex;
use Albet\Asmvc\Core\Cli\ResetRouter;

class Cli extends BaseCli
{
    use Cleanup, ResetRouter, ResetIndex;

    /**
     * Parsing the argument
     * @param $args
     */
    public function argument_parse($args)
    {
        $this->baseparse($args);
        $command_lists = [];
        $loader = new Loader;
        $lists = $loader->load();
        foreach ($lists as $list) {
            $call_cli = new $list;
            $command_lists[] = [
                'command' => $call_cli->getCommand(),
                'desc' => $call_cli->getDesc(),
                'objectclass' => $call_cli
            ];
        }
        $help = <<<Help
        Welcome to ASMVC CLI.
        Some commands you can use:\n\n
        Help;
        foreach ($command_lists as $command_list) {
            $help .= "{$command_list['command']} | {$command_list['desc']}\n";
        }
        foreach ($command_lists as $command_list) {
            $first = strtolower($this->getArgsStarts());
            if ($first == 'help') {
                echo $help . PHP_EOL;
                break;
            } else if ($first == $command_list['command']) {
                $command_list['objectclass']->register();
                break;
            } else {
                echo 'Command not found. Please run "php asmvc help"' . PHP_EOL;
                exit;
                break;
            }
        }
    }
}
