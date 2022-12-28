<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\Traits\FileBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTest extends Command
{
    protected array $identifier = ["create:test", "make:test"];
    use FileBuilder;

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        $fileName = $inputInterface->getArgument('fileName');

        $checkContainsTest = str_contains($fileName, "Test");
        if ($checkContainsTest) {
            $fileContent = <<<content
            <?php
    
            namespace App\Asmvc\Tests;

            use PHPUnit\Framework\TestCase;

            class $fileName extends TestCase
            {
                //Your logic
            }

            content;
        } else {
            $fileContent = <<<content
            <?php

            namespace App\Asmvc\Tests;

            use PHPUnit\Framework\TestCase;

            class {$fileName}Test extends TestCase
            {
                //Your logic
            }
            
            content;
        }

        $parsedName = $checkContainsTest ? $fileName : "{$fileName}Test";

        return $this->buildFile("App/Tests/$parsedName", $fileContent);
    }
}
