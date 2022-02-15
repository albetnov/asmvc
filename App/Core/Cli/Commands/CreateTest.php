<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;

class CreateTest extends BaseCli
{
    /**
     * @var string $command
     * @var string $hint
     * @var string $desc
     */
    protected $command = 'create:test';
    protected $hint = "test";
    protected $desc = 'Creating test';

    /**
     * Register the command
     */
    public function register()
    {
        $try = $this->next_arguments(1);
        if ($try) {
            if (str_contains(strtolower($try), 'test')) {
                $data = <<<data
                        <?php
    
                        namespace Albet\Asmvc\Tests;
    
                        use PHPUnit\Framework\TestCase;
    
                        class {$try} extends TestCase
                        {
                            //Your logic
                        }
    
                        data;
                file_put_contents(base_path() . "App/Tests/{$try}.php", $data);
                echo "Test Created: {$try}.php\n";
                exit;
            } else {
                $data = <<<data
                        <?php

                        namespace Albet\Asmvc\Tests;

                        use PHPUnit\Framework\TestCase;

                        class {$try}Test extends TestCase
                        {
                            //Your logic
                        }

                        data;
                file_put_contents(base_path() . "App/Tests/{$try}Test.php", $data);
                echo "Test Created: {$try}Test.php\n";
                exit;
            }
        }
    }
}
