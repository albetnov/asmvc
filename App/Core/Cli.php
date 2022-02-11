<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\Cli\Loader;

class Cli extends BaseCli
{

    /**
     * Parsing every multiple hint or option.
     * @param string $string
     * @return string
     */
    private function multiple_parse($string)
    {
        $result = "";
        if (!empty($string)) {
            if (str_contains($string, ',')) {
                $split = explode(',', $string);
                foreach ($split as $split) {
                    $result .= " {{$split}}";
                }
                $result .= " ";
            } else {
                $result = " {{$string}} ";
            }
            return $result;
        }
    }

    /**
     * Parsing the argument
     * @param $args
     */
    public function argument_parse($args)
    {
        $command_lists = [];
        $loader = new Loader;
        $lists = $loader->load();
        foreach ($lists as $list) {
            $call_cli = new $list;
            $command_lists[] = [
                'command' => $call_cli->getCommand(),
                'desc' => $call_cli->getDesc(),
                'hint' => $call_cli->getHint(),
                'objectclass' => $call_cli
            ];
        }
        $help = <<<Help
        Welcome to ASMVC CLI.
        Some commands you can use:\n\n
        Help;
        foreach ($command_lists as $command_list) {
            $hint = $this->multiple_parse($command_list['hint']);
            $help .= "{$command_list['command']}{$hint}| {$command_list['desc']}\n";
        }
        $this->baseparse($args);
        $first = strtolower($this->getArgsStarts());
        foreach ($command_lists as $command_list) {
            $command_found = false;
            if ($first == 'help') {
                $command_found = true;
                echo $help . PHP_EOL;
                break;
            }
            if ($first == $command_list['command']) {
                $command_list['objectclass']->register();
                $command_found = true;
                break;
            }
        }
        if ($command_found != true) {
            echo "Command not found. Please run 'php asmvc help'";
        }
    }
}
