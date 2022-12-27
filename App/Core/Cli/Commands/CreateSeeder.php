<?php

namespace App\Asmvc\Core\Cli\Commands;

use App\Asmvc\Core\Cli\Cli;

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

            namespace App\Asmvc\Database\Seeders;

            use App\Asmvc\Core\Seeders\Seeders;
            use Faker\Generator;

            class {$try} extends Seeders
            {
                public function run(): void
                {
                    /**
                     * 
                     * Fluent Seeder
                     * 
                     * Please visit https://albetnov.github.io/asmvc-docs/#/seeder for more
                     * documentation
                     * 
                     **/
                     
                    \$this->fake(1, fn(Generator \$fake) => [
                        'key' => \$fake->name()
                    ])
                    ->done(); // mark as finish 
                }
            }
                 
            data;
            file_put_contents(base_path() . "App/Database/Seeders/{$try}.php", $data);
            echo "Seeder Created: {$try}.php\n";
        }
    }
}
