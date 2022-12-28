<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Contracts\PromptableValue;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Setup extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName("setup")
            ->setDesc("Setting up your web project's environment in matter of seconds.");
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        if (file_exists(base_path(".env"))) {
            $this->error(".env File already exist. Aborting.");
            return Command::FAILURE;
        }

        $wantSetup = $this->prompt("Would you like to setting up ASMVC? ");

        if (!($wantSetup instanceof PromptableValue)) {
            return Command::FAILURE;
        }

        if ($wantSetup === PromptableValue::NO) {
            $this->info("Aborting...");
            return Command::INVALID;
        }

        $dbUser = $this->ask("What is your database user name", ["root"], "root");
        $dbHost = $this->ask("What is your database host", ["localhost"], "localhost");
        $dbPasword = $this->ask("What is your database password", null, "");
        $dbName = $this->ask("What is your database name", ['asmvc'], 'asmvc');

        do {
            $sessionDefault = $this->ask("Which one do you prefer as Session Driver [php/redis]", ['php', 'redis'], 'php');
        } while ($sessionDefault != 'redis' && $sessionDefault != 'php');

        $data = <<<data
        APP_ENV=development
        APP_MODELS_DRIVER=asmvc
        APP_VIEW_ENGINE=latte
        ROUTING_DRIVER=new

        DATABASE_HOST={$dbHost}
        DATABASE_USERNAME={$dbUser}
        DATABASE_PASSWORD={$dbPasword}
        DATABASE_NAME={$dbName}

        SESSION_TYPE={$sessionDefault}
        REDIS_SERVER=127.0.0.1
        REDIS_PORT=6379
        REDIS_DB_NUMBER=0
        REDIS_AUTH_PASS=
        data;

        file_put_contents(base_path('.env'), $data);

        $this->success(".env Generated.");

        return Command::SUCCESS;
    }
}
