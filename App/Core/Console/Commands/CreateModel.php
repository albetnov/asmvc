<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModel extends Command
{
    protected array $identifier = ["create:model", "make:model"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        if (provider_config()['model'] === 'eloquent') {
            $fileContent = <<<content
            <?php

            namespace App\Asmvc\Models;

            use App\Asmvc\Core\Eloquent\Eloquent;
            
            class $fileName extends Eloquent 
            {
                protected string \$table = '';
            }
          
            content;
        } else {
            $fileContent = <<<content
            <?php
    
            namespace App\Asmvc\Models;

            use App\Asmvc\Core\Database\Model;

            class $fileName extends Model
            {
                protected string \$table = '';
            }
          
            content;
        }

        return $this->buildFile("App/Models/$fileName", $fileContent);
    }
}
