<?php

namespace Albet\Asmvc\Core;

class Cli
{
    /**
     * Get where the arguments start
     * @param $args, boolean $get_counter
     * @return int | string
     */
    private function getArgsStarts($args, $get_counter = false)
    {
        if ($args[0] == 'asmvc') {
            if ($get_counter) {
                return 1;
            } else {
                return $args[1];
            }
        } else {
            if ($get_counter) {
                return 0;
            } else {
                return $args[0];
            }
        }
    }

    /**
     * Get the next argument
     * @param $args, int $i
     * @return string | boolean
     */
    private function next_arguments($args, $i)
    {
        $i += $this->getArgsStarts($args, true);
        if (isset($args[$i])) {
            return $args[$i];
        } else {
            return false;
        }
    }

    /**
     * Delete files or directory in specific folder to cleanup.
     * @param string $path, array $diffed.
     */
    private function cleanUp($path, $diffed)
    {
        $dirtho = [];
        foreach ($diffed as $diffed) {
            if (!str_contains($diffed, '.')) {
                $dirs = array_diff(scandir($path . $diffed . '/'), ['.', '..']);
                $dirtho[] = $diffed;
                foreach ($dirs as $dir) {
                    echo "Deleted: $dir\n";
                    unlink($path . $diffed . '/' . $dir);
                }
            } else {
                echo "Deleted: $diffed\n";
                unlink($path . $diffed);
            }
            if (!is_null($dirtho)) {
                foreach ($dirtho as $dir) {
                    echo "Deleted: $dir\n";
                    @rmdir($path . $dir);
                }
            }
        }
    }

    /**
     * Ask a question in CLI.
     * @param string $question
     * @return string $q
     */
    private function ask($question)
    {
        $q = readline($question . ' ? ');
        return $q;
    }

    /**
     * Reset Router.php to clear state.
     */
    private function resetRouter()
    {
        $data = <<<'data'
                <?php

                use Albet\Asmvc\Core\Route;
                
                /**
                 * You can use following method for routing:
                 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod, $Middleware).
                 * Route::inline($urlPath, $CallableFunction, $httpMethod, $Middleware).
                 * $httpMethod and $middleWare can be optional.
                 * It can either be
                 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod) for Http method only
                 * or
                 * Route::add($urlPath, [Controller::class, 'methodName'], $Middleware) for Middleware only.
                 * or both of them.
                 * The same rules applies for inline. 
                 */
                
                
                //Your route
                data;
        file_put_contents(__DIR__ . "/../Router/url.php", $data);
        echo "File: url.php | Route resetted\n";
    }

    /**
     * Reset Index.php to Boostrap Ready/Not state.
     * @param string $options
     */
    private function resetIndex($options = null)
    {
        if ($options == 'add_bs') {
            $data = <<<'data'
            <?php

            require_once __DIR__ . '/../App/Core/init.php';

            use Albet\Asmvc\Core\Route;

            csrf()->generateCsrf();
            define('BS5_CSS', 'css/bootstrap.min.css');
            define('BS5_JS', 'js/bootstrap.min.js');

            /**
             * Calling your route
             */
            require_once __DIR__ . '/../App/Router/url.php';
            Route::triggerRouter();
    
            data;
        } else {
            $data = <<<'data'
            <?php

            require_once __DIR__ . '/../App/Core/init.php';

            use Albet\Asmvc\Core\Route;

            csrf()->generateCsrf();
            define('BS5_CSS', '');
            define('BS5_JS', '');

            /**
             * Calling your route
             */
            require_once __DIR__ . '/../App/Router/url.php';
            Route::triggerRouter();

            data;
        }

        file_put_contents(base_path() . 'public/index.php', $data);
    }

    /**
     * Function to do composer install automatically
     */
    public function install()
    {
        if (!function_exists('exec')) {
            throw new \Exception("Exec() function not detected. Please activate it in php.ini");
        }
        exec('composer install');
        if (!is_dir(base_path('vendor'))) {
            throw new \Exception("Composer install failed to ran.");
        } else {
            echo "Instalisasi selesai!\n";
        }
    }

    /**
     * Parsing the argument
     * @param $args
     */
    public function argument_parse($args)
    {
        $first = strtolower($this->getArgsStarts($args));
        switch ($first) {
            case 'help':
                echo <<<Help
                Welcome to ASMVC CLI.
                Some commands you can use:

                serve | Start a ASMVC Development Server
                install:boostrap | Install and use bootstrap with asset()
                create:controller {controller} | Creating Controller
                create:model {model} | Creating model
                create:middleware {middleware} | Creating Middleware
                create:test {test} | Creating Tests
                run:tests {test?} | Running Test
                reset:router | Switching route file with the fresh install state
                export:core | Exporting specific core files.
                cleanup | Clean up ASMVC to fresh install state. (Controller, Models, Middleware, dan View will get deleted).
                version | Show ASMVC Version.

                Help;
                break;

                // Install a bootstrap to public folder
            case 'install:bootstrap':
                $path = array_diff(scandir(__DIR__ . '/bs5_integration/css/'), ['.', '..']);
                if (!is_dir(public_path() . 'css/')) {
                    mkdir(public_path() . 'css/');
                }
                foreach ($path as $file) {
                    copy(__DIR__ . '/bs5_integration/css/' . $file, public_path() . 'css/' . $file);
                }
                $pathjs = array_diff(scandir(__DIR__ . '/bs5_integration/js/'), ['.', '..']);
                if (!is_dir(public_path() . 'js/')) {
                    mkdir(public_path() . 'js/');
                }
                foreach ($pathjs as $js) {
                    copy(__DIR__ . '/bs5_integration/js/' . $js, public_path() . 'js/' . $js);
                }
                $this->resetIndex('add_bs');
                echo 'Bootstrap installed successfully!' . PHP_EOL;
                break;

                //Display current ASMVC Version.
            case 'version':
                echo 'ASMVC Version ' . ASMVC_VERSION . ' ' . ASMVC_STATE . PHP_EOL;
                break;

                //Create a controller. Required name.
            case 'create:controller':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    $data = <<<data
                    <?php

                    namespace Albet\Asmvc\Controllers;
                    
                    use Albet\Asmvc\Core\Requests;
                    
                    class {$try} extends BaseController
                    {
                        //Your logic
                    }                    
                    data;
                    file_put_contents(__DIR__ . "/../Controllers/{$try}.php", $data);
                }
                break;

                //Create a model. Required name.
            case 'create:model':
                $try = $this->next_arguments($args, 1);
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
                    file_put_contents(__DIR__ . "/../Models/{$try}.php", $data);
                }
                break;

                //Create a middleware. Required name.
            case 'create:middleware':
                $try = $this->next_arguments($args, 1);
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
                    file_put_contents(__DIR__ . "/../Middleware/{$try}.php", $data);
                }
                break;

                // Reset router to fresh state
            case 'reset:router':
                $this->resetRouter();
                break;

                // Clean up entire framework to fresh install state.
            case 'cleanup':
                $ask = $this->ask('Are you sure [Y/n]');
                if (strtolower($ask) == 'y') {
                    $controller_path = __DIR__ . '/../Controllers/';
                    $controller_exclude = ['.', '..', 'BaseController.php'];
                    if (ASMVC_STATE == 'Dev') {
                        $controller_exclude[] = 'HomeController.php';
                    }
                    $controller = array_diff(scandir($controller_path), $controller_exclude);
                    $models_path = __DIR__ . '/../Models/';
                    $models = array_diff(scandir($models_path), ['.', '..', 'BaseModel.php']);
                    $middleware_path = __DIR__ . '/../Middleware/';
                    $middleware = array_diff(scandir($middleware_path), ['.', '..']);
                    $views_path = __DIR__ . '/../Views/';
                    $views = array_diff(scandir($views_path), ['..', '.', 'home.php']);
                    $public = array_diff(scandir(public_path()), ['.', '..', '.gitkeep']);
                    $tests_path = __DIR__ . '/../Tests/';
                    $tests = array_diff(scandir($tests_path), ['.', '..', 'ExampleTest.php']);
                    $this->cleanUp($controller_path, $controller);
                    $this->cleanUp($views_path, $views);
                    $this->cleanUp($models_path, $models);
                    $this->cleanUp($middleware_path, $middleware);
                    $this->cleanUp(public_path(), $public);
                    $this->cleanUp($tests_path, $tests);
                    $this->resetRouter();
                    $this->resetIndex();
                    $try = @unlink(base_path() . '.phpunit.result.cache');
                    if ($try) {
                        echo 'Deleted: .phpunit.result.cache';
                    }
                    echo 'Cleaned successfully!' . PHP_EOL;
                } else {
                    echo 'Canceling...' . PHP_EOL;
                }
                break;

                // Run app with PHP Built in server
            case 'serve':
                if (!function_exists('exec')) {
                    throw new \Exception("Exec() is not detected. Please activate it in php.ini");
                }
                $port = 9090;
                $default = @fsockopen('localhost', $port);
                while (is_resource($default)) {
                    echo "Port in use. (:{$port})\n";
                    $port++;
                    echo "Forwanding to (:{$port})\n";
                    fclose($default);
                }
                echo "ASMVC Development Server Start... (http://localhost:{$port})\n";
                exec('php -S localhost:' . $port . ' public/index.php');
                break;

                // Create a tests file
            case 'create:test':
                $try = $this->next_arguments($args, 1);
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
                        file_put_contents(__DIR__ . "/../Tests/{$try}.php", $data);
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
                        file_put_contents(__DIR__ . "/../Tests/{$try}Test.php", $data);
                    }
                }
                break;

                // Run a test file. Optional name
            case 'run:test':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    system('vendor\bin\phpunit --configuration phpunit.xml App/Tests/' . $try . '.php', $result);
                    echo $result;
                } else {
                    system('vendor\bin\phpunit --configuration phpunit.xml', $result);
                    echo $result;
                }
                break;

                // Export a core specific supported Core Files. Required name.
            case 'export:core':
                $try = $this->next_arguments($args, 1);
                if ($try == 'errorPages') {
                    mkdir(__DIR__ . '/../Views/Errors');
                    $list = array_diff(scandir(__DIR__ . '/Errors/'), ['.', '..']);
                    foreach ($list as $file) {
                        copy(__DIR__ . '/Errors/' . $file, __DIR__ . '/../Views/Errors/' . $file);
                        echo "Copied: {$file}\n";
                    }
                    echo "Exported Successfully!\n";
                }
                break;

                //Return this if the command is unrecognized.
            default:
                echo 'Command not found. Please run "php asmvc help"' . PHP_EOL;
                break;
        }
    }
}
