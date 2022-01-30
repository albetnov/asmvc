<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateTest extends BaseCli
{
    protected $command = 'create:test {test}';
    protected $desc = 'Creating test';

    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            if (str_contains('Test', $try)) {
                $data = <<<data
                        <?php
    
                        namespace Albet\Asmvc\Tests;
    
                        require_once __DIR__ . '/../Core/init.php';
                        use PHPUnit\Framework\TestCase;
    
                        class {$try} extends TestCase
                        {
                            //Your logic
                        }
    
                        data;
            } else {
                $data = <<<data
                        <?php

                        namespace Albet\Asmvc\Tests;

                        require_once __DIR__ . '/../Core/init.php';
                        use PHPUnit\Framework\TestCase;

                        class {$try}Test extends TestCase
                        {
                            //Your logic
                        }

                        data;
            }
            file_put_contents(base_path() . "App/Tests/{$try}.php", $data);
        }
    }
}
