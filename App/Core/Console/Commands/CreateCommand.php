<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected array $identifier = ["create:command", "make:command"];
    use FileBuilder;

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

        return $this->buildFile("App/Commands/$fileName", $fileContent);
    }
}
