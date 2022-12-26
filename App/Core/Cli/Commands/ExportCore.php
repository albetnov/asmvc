<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\Cli;

class ExportCore extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = 'export:core';
    protected $hint = "name";
    protected $desc = 'Exporting specific core files';

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try == 'errorPages') {
            mkdir(base_path() . 'App/Views/Errors');
            $list = array_diff(scandir(base_path() . 'App/Core/Errors/'), ['.', '..']);
            foreach ($list as $file) {
                copy(base_path() . 'App/Core/Errors/' . $file, base_path() . 'App/Views/Errors/' . $file);
                echo "Copied: {$file}\n";
            }
            echo "Exported Successfully!\n";
        }
    }
}
