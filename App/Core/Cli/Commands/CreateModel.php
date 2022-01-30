<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateModel extends BaseCli
{
    protected $command = "create:model";
    protected $hint = "model";
    protected $desc = "Creating Model";
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            $data = <<<data
                    <?php

                    namespace Albet\Asmvc\Models;

                    use Albet\Asmvc\Core\BaseModel;

                    class {$try} extends BaseModel
                    {
                        protected \$table = '';
                    }

                    data;
            file_put_contents(base_path() . "App/Models/{$try}.php", $data);
            echo "Model Created: {$try}.php\n";
        }
    }
}
