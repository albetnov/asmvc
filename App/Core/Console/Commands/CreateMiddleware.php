<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMiddleware extends Command
{
    protected array $identifier = ["create:middleware", "make:middleware"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $fileContent = <<<content
        <?php

        namespace App\Asmvc\Middleware;

        use App\Asmvc\Core\Middleware\Middleware;

        class $fileName extends Middleware
        {
           public function middleware(): void {

           }
        }  
      
        content;

        return $this->buildFile("App/Middleware/$fileName", $fileContent);
    }
}
