<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateMigration extends BaseCli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = "create:migration";
    protected $hint = "MigrationName";
    protected $desc = "Create a migration.";

    /**
     * Register the command
     */
    public function register()
    {
        $next = $this->next_arguments(1);
        if ($next) {
            $data = <<<data
            <?php

            namespace Albet\\Asmvc\\Database\\Migrations;

            use Albet\\Asmvc\\Core\\BaseMigration;
            use Illuminate\\Database\\Schema\\Blueprint;

            return new class extends BaseMigration
            {
                public function up(): void
                {
                    \$this->schema->create('', function(Blueprint \$table){
                    
                    });
                }

                public function down(): void
                {
                    \$this->schema->dropIfExists('');
                }
            };

            data;

            $time = time();

            file_put_contents(base_path() . "/App/Database/Migrations/{$next}_{$time}.php", $data);
            echo "Migration creted: {$next}_{$time}.php\n";
        }
    }
}
