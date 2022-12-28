<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Contracts\BadgeColor;
use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentParamBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCore extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName("export:core")
            ->setDesc("Export cores file")
            ->addParam(
                fn (FluentParamBuilder $pb) => $pb
                    ->setName("type")
                    ->setDesc("Core Type to export")
                    ->setInputTypeRequired()
            );
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $type = $inputInterface->getArgument("type");

        $whiteListedCore = ["errorPages"];

        if (!in_array($type, $whiteListedCore)) {
            $this->error("Invalid core files. Supported: " . join(",", $whiteListedCore));
            return Command::FAILURE;
        }

        mkdir(base_path() . 'App/Views/Errors');
        $list = array_diff(scandir(base_path() . 'App/Core/Errors/'), ['.', '..']);
        foreach ($list as $file) {
            copy(base_path() . 'App/Core/Errors/' . $file, base_path() . 'App/Views/Errors/' . $file);
            $this->badge("Copied: $file", "INFO:", BadgeColor::Blue);
        }

        $this->success("Exported Successfully!");

        return Command::SUCCESS;
    }
}
