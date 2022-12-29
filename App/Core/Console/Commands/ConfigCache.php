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

            if ((count($configs) <= 0)) {
                continue; // skip this
            }

            foreach ($configs as $key => $value) {
                if (is_bool($value)) {
                    $value = $value ? "true" : "false";
                    $parsed .= "\t'$key' => $value, \n";
                } else if (is_int($value)) {
                    $parsed .= "\t'$key' => $value, \n";
                } else if (is_array($value)) {
                    $joined = implode(",", $value);
                    $parsed .= "\t '$key' => ['$joined'],";
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
