<?php

namespace Albet\Asmvc\Core\Cli;

class BaseCli
{
    /**
     * @var $args
     */
    protected static $args = [];
    protected $hint;

    /**
     * Get where the arguments start
     * @param $args, boolean $get_counter
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
     * @param $args, int $i
     * @return string | boolean
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
     * @return string $q
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
            if (!is_file(base_path('.env'))) {
                $prompt_setup = $this->ask("Welcome to ASMVC First Time Setup. Would you like to setup your ASMVC [Y/n]");
                if (strtolower($prompt_setup) == 'y') {
                    $db_host = $this->ask("What's your database host (Default: Localhost)", 'localhost');
                    $db_user = $this->ask("What's your database username (Default: root)", 'root');
                    $db_pass = $this->ask("What's your database password");
                    $db_name = $this->ask("What's your database name (Default: asmvc)", 'asmvc');
                    do {
                        $session_default = $this->ask("Which one do you prefer as Session Driver (Default: redis) [php/redis]", 'redis');
                    } while ($session_default != 'redis' && $session_default != 'php');
                    $data = <<<data
                    APP_ENV=production
                    APP_MODELS_DRIVER=asmvc

                    DATABASE_HOST={$db_host}
                    DATABASE_USERNAME={$db_user}
                    DATABASE_PASSWORD={$db_pass}
                    DATABASE_NAME={$db_name}

                    ENTRY_TYPE=controller
                    ENTRY_CLASS=HomeController
                    ENTRY_METHOD=index
                    ENTRY_MIDDLEWARE=

                    SESSION_TYPE={$session_default}
                    REDIS_SERVER=127.0.0.1
                    REDIS_PORT=6379
                    REDIS_DB_NUMBER=0
                    REDIS_AUTH_USER=
                    REDIS_AUTH_PASS=
                    data;
                    file_put_contents(base_path('.env'), $data);
                } else {
                    echo "Skipping...\n";
                }
            }
            echo "Instalilation Completed!\n";
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
