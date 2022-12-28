<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigration extends Command
{
    protected array $identifier = ["create:migration", "make:migration"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $fileContent = <<<content
        <?php

        namespace App\\Asmvc\\Database\\Migrations;

        use App\\Asmvc\\Core\\Migration;
        use Illuminate\\Database\\Schema\\Blueprint;

        return new class extends Migration
        {
            public function up(): void
            {
                \$this->schema->create('', function(Blueprint \$table){
                
                });
            }

            public function down(): void
            {
                \$this->schema->dropIfExists('');
            }
        };
      
        content;

        $time = time();

        return $this->buildFile("App/Database/Migrations/{$fileName}_{$time}", $fileContent);
    }
}
