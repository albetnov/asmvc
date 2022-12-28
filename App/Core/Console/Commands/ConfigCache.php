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
        $allConfigs = array_values(array_diff(scandir(config_path()), ['.', '..', 'container.php']));

        foreach ($allConfigs as $config) {
            $name = explode(".", $config)[0];
            $configs = config($name);

            // evaluate all possible expression that has in array
            // LIMITATIONS: Only able to evaluate a helpers function or something that not use namespace alias.
            $parsed = "";


            foreach ($configs as $key => $value) {
                if ($value === true) {
                    $parsed .= "\t'$key' => true, \n";
                } else {
                    $parsed .= "\t'$key' => '$value',\n";
                }
            }
            $parsed = substr($parsed, 0, -2);
            $parsedPhpFile = <<<content
            <?php

            return [
            $parsed
            ];
            content;
            file_put_contents(cache_path($name . ".php"), $parsedPhpFile);
            $this->badgeSuccess("Cached $config");
        }

        $this->info("Cached successfully!");

        return Command::SUCCESS;
    }
}
