<?php

namespace App\Asmvc\Core\Console\Commands;

use App\Asmvc\Core\Console\Command;
use App\Asmvc\Core\Console\FluentCommandBuilder;
use App\Asmvc\Core\Console\FluentOptionalParamBuilder;
use App\Asmvc\Database\Sorter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunSeeder extends Command
{
    protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder
    {
        return $builder->setName('run:seeder')
            ->setAliases('seed:db')
            ->setDesc("Seed your database entry")
            ->addOptionalParam(
                fn (FluentOptionalParamBuilder $opb) => $opb
                    ->setName('class')
                    ->setInputTypeRequired()
                    ->setShortcut('c')
                    ->setDesc('File class name')
            )->addOptionalParam(
                fn (FluentOptionalParamBuilder $opb) => $opb->setName('no-sort')
                    ->setDesc('Ignore Sorter.php')
                    ->setInputTypeNone()
                    ->setShortcut('ns')
            );
    }


    private function noExtension(string $file): string
    {
        $result = explode('.', $file);
        array_pop($result);
        return implode('.', $result);
    }

    public function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int
    {
        if ($inputInterface->getOption('class')) {
            $className = $inputInterface->getOption('class');
            $class = "\\Albet\\Asmvc\\Database\\Seeders\\{$className}";
            (new $class())->run();
            $this->badgeSuccess("Seeded: {$className}");
            return Command::SUCCESS;
        }

        $diffed = array_diff(scandir(base_path() . "/App/Database/Seeders"), ['.', '..', '.gitkeep']);

        $sorter = new Sorter();

        if ($sorter->migrations() && !$inputInterface->getOption('no-sort')) {
            $diffed = collect($sorter->seeders())->map(fn ($item) => "{$item}.php");
        }

        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    $this->badgeSuccess("Seeded: {$dir}.");
                    $noext = $this->noExtension($dir);
                    $class = "\\Albet\\Asmvc\\Database\\Seeders\\{$noext}";
                    (new $class())->run();
                }
            } else {
                $this->badgeSuccess("Seeded: {$diffed}.");
                $noext = $this->noExtension($diffed);
                $class = "\\Albet\\Asmvc\\Database\\Seeders\\{$noext}";
                (new $class())->run();
            }
        }

        $this->success("Seeding has completed successfully.");

        return Command::SUCCESS;
    }
}
