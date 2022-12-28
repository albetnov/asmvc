<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateController extends Command
{
    protected array $identifier = ["create:controller", "make:controller"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $fileContent = <<<content
        <?php

        namespace App\Asmvc\Controllers;

        use App\Asmvc\Core\Requests;

        class $fileName
        {
            public function index()
            {
            }
        }
      
        content;

        return $this->buildFile("App/Controllers/$fileName", $fileContent);
    }
}
