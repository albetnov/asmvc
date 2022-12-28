<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentParamBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName('create:command')
            ->setDesc("Create a command file for you.")
            ->setAliases("make:command")
            ->addParam(fn (FluentParamBuilder $pb) => $pb->setName('fileName')
                ->setDesc("File Name to generate")
                ->setInputTypeRequired());
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $fileContent = <<<content
        <?php

        namespace App\Asmvc\Core\Console\Commands;
        
        use App\Asmvc\Core\Console\Command;
        use App\Asmvc\Core\Console\FluentCommandBuilder;
        use App\Asmvc\Core\Console\FluentOptionalParamBuilder;
        use App\Asmvc\Core\Console\FluentParamBuilder;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;
        
        class $fileName extends Command
        {
            protected function setup(FluentCommandBuilder \$builder): FluentCommandBuilder
            {
                return \$builder->setName('command:name')
                    ->setDesc("Command Description")
                    ->setAliases("command:alias")
                    ->addParam(fn (FluentParamBuilder \$pb) => \$pb->setName('argName'))
                    ->addOptionalParam(fn(FluentOptionalParamBuilder \$opb) => \$opb->setName('optional'));
            }
        
            public function handler(InputInterface \$inputInterface, OutputInterface \$outputInterface): int
            {
                return Command::SUCCESS;
            }
        }            
        content;

        $path = base_path("App/Commands/{$fileName}.php");

        if (file_exists($path)) {
            $this->error("Ups, file already exist. Aborting");
            return Command::FAILURE;
        }

        file_put_contents($path, $fileContent);

        $this->success("Command file generated in: $path");

        return Command::SUCCESS;
    }
}
