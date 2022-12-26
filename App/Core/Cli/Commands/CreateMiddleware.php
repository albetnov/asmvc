<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\Cli;

class CreateMiddleware extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "create:middleware";
    protected $hint = "middleware";
    protected $desc = "Create a Middleware";

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

             namespace Albet\Asmvc\Middleware;

             use Albet\Asmvc\Core\Middleware\Middleware;

             class {$try} extends Middleware
             {
                 public function middleware(): void
                 {
                    
                 }
             }  
            data;
            file_put_contents(base_path() . "App/Middleware/{$try}.php", $data);
            echo "Middleware Created: {$try}.php\n";
        }
    }
}
