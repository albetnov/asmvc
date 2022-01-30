<?php

namespace Albet\Asmvc\Core\Cli;

class BaseCli
{
    /**
     * @var $args
     */
    protected $args;

    /**
     * Get where the arguments start
     * @param $args, boolean $get_counter
     * @return int | string
     */
    protected function getArgsStarts($get_counter = false)
    {
        if ($this->args[0] == 'asmvc') {
            if ($get_counter) {
                return 1;
            } else {
                return $this->args[1];
            }
        } else {
            if ($get_counter) {
                return 0;
            } else {
                return $this->args[0];
            }
        }
    }

    /**
     * Get the next argument
     * @param $args, int $i
     * @return string | boolean
     */
    protected function next_arguments($i)
    {
        $i += $this->getArgsStarts($this->args, true);
        if (isset($this->args[$i])) {
            return $this->args[$i];
        } else {
            return false;
        }
    }

    /**
     * Ask a question in CLI.
     * @param string $question
     * @return string $q
     */
    protected function ask($question)
    {
        $q = readline($question . ' ? ');
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
            echo "Instalisasi selesai!\n";
        }
    }

    /**
     * Put args as variable
     * @param string $args
     */
    protected function baseparse($args)
    {
        $this->args = $args;
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
}
