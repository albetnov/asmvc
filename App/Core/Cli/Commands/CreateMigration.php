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
            if ($this->next_arguments(2) == 'anonymous') {
                $data = <<<data
            <?php

            namespace Albet\\Asmvc\\Database\\Migrations;

            use Albet\\Asmvc\\Core\\BaseMigration;
            use Illuminate\\Database\\Schema\\Blueprint;

            return new class extends BaseMigration
            {
                public function up()
                {
                    \$this->schema->create('', function(Blueprint \$table){
                    
                    });
                }

                public function down()
                {
                    \$this->schema->dropIfExists('');
                }
            };

            data;
            } else {
                $data = <<<data
            <?php

            namespace Albet\\Asmvc\\Database\\Migrations;

            use Albet\\Asmvc\\Core\\BaseMigration;
            use Illuminate\\Database\\Schema\\Blueprint;

            class {$next} extends BaseMigration
            {
                public function up()
                {
                    \$this->schema->create('', function(Blueprint \$table){
                    
                    });
                }

                public function down()
                {
                    \$this->schema->dropIfExists('');
                }
            };

            data;
            }
            file_put_contents(base_path() . "/App/Database/Migrations/{$next}.php", $data);
            echo "Migration creted: {$next}.php\n";
        }
    }
}
