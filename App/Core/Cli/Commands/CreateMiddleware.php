<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateMiddleware extends BaseCli
{
    protected $command = "create:middleware {middleware}";
    protected $desc = "Creating Middleware";

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

             namespace Albet\Asmvc\Middleware;

             use Albet\Asmvc\Core\BaseMiddleware;

             class {$try} extends BaseMiddleware
             {
                 public function middleware()
                 {
                    
                 }
             }  
            data;
            file_put_contents(base_path() . "App/Middleware/{$try}.php", $data);
        }
    }
}
