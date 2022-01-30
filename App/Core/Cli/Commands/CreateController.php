<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateController extends BaseCli
{
    protected $command = "create:controller {controller}";
    protected $desc = "Creating Controller";

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

            namespace Albet\Asmvc\Controllers;
            
            use Albet\Asmvc\Core\Requests;
            
            class {$try} extends BaseController
            {
                //Your logic
            }                    
            data;
            file_put_contents(base_path() . "App/Core/Controllers/{$try}.php", $data);
        }
    }
}
