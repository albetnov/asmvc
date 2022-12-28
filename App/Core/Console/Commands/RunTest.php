<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentOptionalParamBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTest extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName('run:test')
            ->setAliases('test')
            ->setDesc("Run your test file")
            ->addOptionalParam(fn (FluentOptionalParamBuilder $opb): FluentOptionalParamBuilder => $opb->setName('fileName')
                ->setInputTypeRequired()
                ->setDesc('Your test file name.'));
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $path = PHP_OS_FAMILY == 'windows' ? 'vendor\\bin\\phpunit' : 'vendor/bin/phpunit';
        if ($inputInterface->getOption('fileName')) {
            $fileName = $inputInterface->getOption('fileName');
            if (!function_exists('system')) {
                $this->badgeError('system function is inaccesible.');
                return Command::FAILURE;
            }
            system("{$path} --configuration phpunit.xml App/Tests/{$fileName}.php", $result);
            $this->info($result);
        } else {
            system("{$path} --configuration phpunit.xml", $result);
            $this->info($result);
        }

        return Command::SUCCESS;
    }
}
