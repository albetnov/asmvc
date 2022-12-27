<?php

namespace App\Asmvc\Core;

use App\Asmvc\Core\Cli\Cli;
use App\Asmvc\Core\Cli\Loader;

class CliParser extends Cli
{

    /**
     * Parsing every multiple hint or option.
     * @param string $string
     * @return string
     */
    private function multiple_parse(?string $string): ?string
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

        return null;
    }

    /**
     * Parsing the argument
     * @param array $args
     */
    public function argument_parse(array $args): void
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
            echo "Command not found. Please run 'php asmvc help'\n";
        }
    }
}
