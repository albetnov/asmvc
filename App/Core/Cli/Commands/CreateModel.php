<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\Config;

class CreateModel extends BaseCli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "create:model";
    protected $hint = "model";
    protected $desc = "Creating Model";

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $env = Config::modelDriver();
            if ($env == 'eloquent') {
                $data = <<<data
                        <?php

                        namespace Albet\Asmvc\Models;

                        use Albet\Asmvc\Core\BaseEloquent;
                        
                        class {$try} extends BaseEloquent 
                        {
                            protected string \$table = '';
                        }

                        data;
            } else {
                $data = <<<data
                        <?php
    
                        namespace Albet\Asmvc\Models;
    
                        use Albet\Asmvc\Core\BaseModel;
    
                        class {$try} extends BaseModel
                        {
                            protected string \$table = '';
                        }
    
                        data;
            }
            file_put_contents(base_path() . "App/Models/{$try}.php", $data);
            echo "Model Created: {$try}.php\n";
        }
    }
}
