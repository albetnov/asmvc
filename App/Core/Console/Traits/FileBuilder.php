<?php

namespace App\Asmvc\Core\Console\Traits;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentParamBuilder;

trait FileBuilder
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        $parsedName = explode(":", $this->identifier[0])[1];
        return $builder->setName($this->identifier[0])
            ->setDesc("Create a {$parsedName} file for you.")
            ->setAliases($this->identifier[1])
            ->addParam(fn (FluentParamBuilder $pb): FluentParamBuilder => $pb->setName('fileName')
                ->setDesc("File Name to generate")
                ->setInputTypeRequired());
    }

    public function buildFile($path, $content): int
    {
        $path = base_path($path . ".php");

        if (file_exists($path)) {
            $this->error("Ups, file already exist. Aborting");
            return Command::FAILURE;
        }

        file_put_contents($path, $content);

        $this->success("Command file generated in: $path");

        return Command::SUCCESS;
    }
}
