<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\Cli;

class CreateSeeder extends Cli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "create:seeder";
    protected $hint = "SeederName";
    protected $desc = "Create a Seeder";

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
            <?php

            namespace Albet\Asmvc\Database\Seeders;

            use Albet\Asmvc\Core\Seeders;

            class {$try} extends Seeders
            {
                public function run(): void
                {
                    /**
                     * @param int \$count
                     * @param string|callable \$table
                     * @param array \$data
                     * count() method is optional.
                     */
                    \$this->count(\$count)->seed(\$table, \$data);
                }
            }
                 
            data;
            file_put_contents(base_path() . "App/Database/Seeders/{$try}.php", $data);
            echo "Seeder Created: {$try}.php\n";
        }
    }
}
