<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCache extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName("cache:config")
            ->setDesc("Cache the config file")
            ->setAliases('config:cache');
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        /**
         * @TODO
         * parse all config files
         * exclude container.php
         * turn them to json
         * write the file
         * detect if json file exist
         * use the cached config.
         */

        // something like below but use scandir().
        // parse the cache name by splitting extension
        // exclude ['container.php', '.', '..'] using array_diff_key
        file_put_contents(cache_path("database.json"), json_encode(config('database')));
        $this->badgeSuccess("Cached database.php");

        return Command::SUCCESS;
    }
}
