<?php

namespace Albet\Asmvc\Core\Cli;

class Cli
{
    /**
     * @var array $args
     * @var string $hint
     */
    protected static $args = [];
    protected $hint;

    private function getArg(int $index): string
    {
        if (!isset(self::$args[$index])) {
            throw new ArgumentInvalidException();
        }
        return self::$args[$index];
    }

    /**
     * Get where the arguments start
     * @param bool $get_counter
     * @return int | string
     */
    protected function getArgsStarts(bool $get_counter = false): int | string
    {
        if (self::$args[0] == 'asmvc') {
            if ($get_counter) {
                return 1;
            } else {
                return $this->getArg(1);
            }
        } else {
            if ($get_counter) {
                return 0;
            } else {
                return $this->getArg(0);
            }
        }
    }

    /**
     * Get the next argument
     * @param int $i
     * @return string|bool
     */
    protected function next_arguments(int $i): string | bool
    {
        $i += $this->getArgsStarts(true);
        if (isset(self::$args[$i])) {
            return self::$args[$i];
        } else {
            return false;
        }
    }

    /**
     * Ask a question in CLI.
     * @param string $question
     * @return string
     */
    protected function ask(string $question, ?string $default = null): string
    {
        $q = readline($question . ' ? ');
        if (!$q && $default) {
            return $default;
        }
        return $q;
    }

    /**
     * Function to do composer install automatically
     */
    public function install(): void
    {
        if (!function_exists('exec')) {
            throw new ExecDisabledException();
        }
        exec('composer install');
        if (!is_dir(base_path('vendor'))) {
            throw new ComposerInstallFailedException();
        } else {
            exec('php asmvc setup');
        }
    }

    /**
     * Put args as variable
     * @param array $args
     */
    protected function baseparse(array $args): void
    {
        self::$args = $args;
    }

    /**
     * Get command
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Get Descriptions
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Get hint
     * @return string
     */
    public function getHint()
    {
        if (isset($this->hint)) {
            return $this->hint;
        } else {
            return;
        }
    }
}
