<?php

namespace App\Asmvc\Core\Cli\Commands;

use App\Asmvc\Core\Cli\Cli;

class CreateController extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "create:controller";
    protected $hint = "controller";
    protected $desc = "Create a Controller";

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

            namespace App\Asmvc\Controllers;
            
            use App\Asmvc\Core\Requests;
            
            class {$try}
            {
                //Your logic
            }                    
            data;
            file_put_contents(base_path() . "App/Controllers/{$try}.php", $data);
            echo "Controller Created: {$try}.php\n";
        }
    }
}
