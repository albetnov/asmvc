<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\Cli;

class Setup extends Cli
{
    /**
     * @var string $command
     * @var string $desc
     */
    protected $command = "setup";
    protected $desc = "Setting up ASMVC.";

    /**
     * Register the command
     */
    public function register()
    {
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
                APP_ENV=development
                APP_MODELS_DRIVER=asmvc
                APP_VIEW_ENGINE=latte

                DATABASE_HOST={$db_host}
                DATABASE_USERNAME={$db_user}
                DATABASE_PASSWORD={$db_pass}
                DATABASE_NAME={$db_name}

                SESSION_TYPE={$session_default}
                REDIS_SERVER=127.0.0.1
                REDIS_PORT=6379
                REDIS_DB_NUMBER=0
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
