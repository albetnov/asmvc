<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class RunSeeder extends BaseCli
{
    protected $command = "run:seeder";
    protected $hint = "SeederName?";
    protected $desc = "Seeding a database table.";

    private function noExtension($file)
    {
        $result = explode('.', $file);
        array_pop($result);
        return implode('.', $result);
    }

    public function register()
    {
        $try = $this->next_arguments(1);

        if ($try) {
            $class = "\Albet\Asmvc\Database\Seeders\{$try}";
            (new $class())->run();
            echo "Seeded: {$try}\n";
        } else {
            $diffed = array_diff(scandir(base_path() . "/App/Database/Seeders"), ['.', '..', '.gitkeep']);
            $dirtho = [];
            foreach ($diffed as $diffed) {
                if (!str_contains($diffed, '.')) {
                    $dirs = array_diff(scandir($diffed . '/'), ['.', '..']);
                    $dirtho[] = $diffed;
                    foreach ($dirs as $dir) {
                        echo "Seeded: {$dir}.\n";
                        $noext = $this->noExtension($dir);
                        $class = "\\Albet\\Asmvc\\Database\\Seeders\\{$noext}";
                        (new $class())->run();
                    }
                } else {
                    echo "Seeded: {$diffed}\n";
                    $noext = $this->noExtension($diffed);
                    $class = "\\Albet\\Asmvc\\Database\\Seeders\\{$noext}";
                    (new $class())->run();
                }
            }
        }
    }
}
