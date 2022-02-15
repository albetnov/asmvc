<?php

namespace Albet\Asmvc\Core\Cli;

class BaseCli
{
    /**
     * @var array $args
     * @var string $hint
     */
    protected static $args = [];
    protected $hint;

    /**
     * Get where the arguments start
     * @param boolean $get_counter
     * @return int | string
     */
    protected function getArgsStarts($get_counter = false)
    {
        if (self::$args[0] == 'asmvc') {
            if ($get_counter) {
                return 1;
            } else {
                return self::$args[1];
            }
        } else {
            if ($get_counter) {
                return 0;
            } else {
                return self::$args[0];
            }
        }
    }

    /**
     * Get the next argument
     * @param int $i
     * @return string|boolean
     */
    protected function next_arguments($i)
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
    protected function ask($question, $default = null)
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
    public function install()
    {
        if (!function_exists('exec')) {
            throw new \Exception("Exec() function not detected. Please activate it in php.ini");
        }
        exec('composer install');
        if (!is_dir(base_path('vendor'))) {
            throw new \Exception("Composer install failed to ran.");
        } else {
            exec('php asmvc setup');
        }
    }

    /**
     * Put args as variable
     * @param string $args
     */
    protected function baseparse($args)
    {
        self::$args = $args;
    }

    /**
     * Get command
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Get Descriptions
     * @return string
     */
    public function getDesc()
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
