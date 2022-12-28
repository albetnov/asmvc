<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallBootstrap extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName("install:bootstrap")
            ->setDesc("Install Bootstrap Framework to your web app");
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        if (!is_dir(base_path('node_modules'))) {
            $this->badgeWarn("Node Modules not detected. ASMVC will tried to run 'npm install'");
            system('npm i');
            if (is_dir(base_path('node_modules'))) {
                $this->badgeSuccess("Command executed successfully");
            } else {
                $this->badgeError("Failed to execute npm install. Please execute it manually.");
                return Command::FAILURE;
            }
        }
        $path = array_diff(scandir(base_path() . 'node_modules/bootstrap/dist/css/'), ['.', '..']);
        if (!is_dir(public_path() . 'css/')) {
            mkdir(public_path() . 'css/');
        }
        foreach ($path as $file) {
            copy(base_path() . 'node_modules/bootstrap/dist/css/' . $file, public_path() . 'css/' . $file);
        }
        $pathjs = array_diff(scandir(base_path() . 'node_modules/bootstrap/dist/js/'), ['.', '..']);
        if (!is_dir(public_path() . 'js/')) {
            mkdir(public_path() . 'js/');
        }
        foreach ($pathjs as $js) {
            copy(base_path() . 'node_modules/bootstrap/dist/js/' . $js, public_path() . 'js/' . $js);
        }

        $this->success('Bootstrap installed successfully!');

        return Command::SUCCESS;
    }
}
