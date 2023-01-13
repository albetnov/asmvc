<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSeeder extends Command
{
    protected array $identifier = ["create:seeder", "make:seeder"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $fileContent = <<<content
        <?php

        namespace App\Asmvc\Database\Seeders;

        use App\Asmvc\Core\Seeders\Seeders;
        use Faker\Generator;

        class $fileName extends Seeders
        {
            public function run(): void
            {
                /**
                 * 
                 * Fluent Seeder
                 * 
                 * Please visit https://albetnov.github.io/asmvc-docs/#/seeder for more
                 * documentation
                 * 
                 **/
                    
                \$this->setTable(\$tableName)
                    ->fake(1, fn(Generator \$fake) => [
                    'key' => \$fake->name()
                    ])
                    ->done(); // mark as finish 
            }
        }
      
        content;

        return $this->buildFile("App/Database/Seeders/$fileName", $fileContent);
    }
}
