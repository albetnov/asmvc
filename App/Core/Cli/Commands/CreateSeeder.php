<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateSeeder extends BaseCli
{
    protected $command = "create:seeder";
    protected $hint = "SeederName";
    protected $desc = "Creating Seeder";

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

            namespace Albet\Asmvc\Database\Seeders;

            use Albet\Asmvc\Core\EloquentDB;
            use Albet\Asmvc\Core\Seeders;

            class {$try} extends Seeders
            {
                public function run()
                {
                    \$this->seed(0, function () {
                        //Your logic
                    });
                }
            }
                 
            data;
            file_put_contents(base_path() . "App/Database/Seeders/{$try}.php", $data);
            echo "Seeder Created: {$try}.php\n";
        }
    }
}
