<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\addBootstrap;
use Albet\Asmvc\Core\Cli\BaseCli;

class InstallBootstrap extends BaseCli
{
    use addBootstrap;

    /**
     * @var string $command
     * @var string $desc
     */
    protected $command = "install:bootstrap";
    protected $desc = "Install and use bootstrap with asset()";

    /**
     * Register the command
     */
    public function register()
    {
        if (!is_dir(base_path('node_modules'))) {
            echo "Node Modules not detected. ASMVC will tried to run 'npm install'.\n";
            system('npm i');
            if (is_dir(base_path('node_modules'))) {
                echo "Command executed successfully \n";
            } else {
                echo "Failed to execute npm install. Please execute it manually.\n";
            }
        }
        $path = array_diff(scandir(base_path() . 'node_modules/bootstrap/dist/css/'), ['.', '..']);
        if (!is_dir(public_path() . 'css/')) {
            mkdir(public_path() . 'css/');
        }
        foreach ($path as $file) {
            copy(base_path() . 'node_modules/bootstrap/dist/css/' . $file, public_path() . 'css/' . $file);
        }
        $pathjs = array_diff(scandir(base_path() . 'node_modules/bootstrap/dist/js/'), ['.', '..']);
        if (!is_dir(public_path() . 'js/')) {
            mkdir(public_path() . 'js/');
        }
        foreach ($pathjs as $js) {
            copy(base_path() . 'node_modules/bootstrap/dist/js/' . $js, public_path() . 'js/' . $js);
        }
        $this->addBootstrap();
        echo 'Bootstrap installed successfully!' . PHP_EOL;
        echo "Note: If bootstrap javascript failed to load. You may need to import popper js.\n";
    }
}
