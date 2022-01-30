<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class ExportCore extends BaseCli
{
    protected $command = 'export:core';
    protected $hint = "name";
    protected $desc = 'Exporting specific core files';
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
