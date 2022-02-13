<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\addBootstrap;
use Albet\Asmvc\Core\Cli\BaseCli;

class InstallBootstrap extends BaseCli
{
    use addBootstrap;

    protected $command = "install:bootstrap";
    protected $desc = "Install and use bootstrap with asset()";

    public function register()
    {
        $path = array_diff(scandir(base_path() . 'App/Core/bs5_integration/css/'), ['.', '..']);
        if (!is_dir(public_path() . 'css/')) {
            mkdir(public_path() . 'css/');
        }
        foreach ($path as $file) {
            copy(base_path() . 'App/Core/bs5_integration/css/' . $file, public_path() . 'css/' . $file);
        }
        $pathjs = array_diff(scandir(base_path() . 'App/Core/bs5_integration/js/'), ['.', '..']);
        if (!is_dir(public_path() . 'js/')) {
            mkdir(public_path() . 'js/');
        }
        foreach ($pathjs as $js) {
            copy(base_path() . 'App/Core/bs5_integration/js/' . $js, public_path() . 'js/' . $js);
        }
        $this->addBootstrap();
        echo 'Bootstrap installed successfully!' . PHP_EOL;
    }
}
