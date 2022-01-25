<?php

namespace Albet\Ppob\Core;

class Cli
{
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

    private function next_arguments($args, $i)
    {
        $i += $this->getArgsStarts($args, true);
        if (isset($args[$i])) {
            return $args[$i];
        } else {
            return false;
        }
    }

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

    private function ask($question)
    {
        $q = readline($question . ' ? ');
        return $q;
    }

    private function resetRouter()
    {
        $data = <<<'data'
                <?php

                namespace Albet\Ppob\Router;

                use Albet\Ppob\Core\BaseRouter;

                class Router extends BaseRouter
                {
                    /**
                     * Anda bisa mendefinisikan routing anda disini.
                     */
                    public function defineRouter(): void
                    {
                        /**
                         * Ada 2 method yang bisa anda gunakan. inline($path, $function, $http_method) dan 
                         * add($path, [controller::class, 'method'], $http_method).
                         */

                        //Your route

                        /**
                         * Menjalankan routing
                         */
                        self::triggerRouter();
                    }
                }   

                data;
        file_put_contents(__DIR__ . "/../Router/Router.php", $data);
    }

    private function resetIndex($options = null)
    {
        if ($options == 'add_bs') {
            $data = <<<'data'
            <?php
    
            require_once __DIR__ . '/App/Core/init.php';
    
            use Albet\Ppob\Router\Router;
    
            csrf()->generateCsrf();
            define('BS5_CSS', 'css/bootstrap.min.css');
            define('BS5_JS', 'js/bootstrap.min.js');
            $router = new Router;
            $router->defineRouter();
    
            data;
        } else {
            $data = <<<'data'
            <?php
    
            require_once __DIR__ . '/App/Core/init.php';
    
            use Albet\Ppob\Router\Router;
    
            csrf()->generateCsrf();
            $router = new Router;
            $router->defineRouter();
    
            data;
        }

        file_put_contents(base_path() . 'index.php', $data);
    }

    public function install()
    {
        if (!function_exists('exec')) {
            throw new \Exception("Exec() tidak terdektsi. Harap aktifkan di php.ini");
        }
        exec('composer install');
        if (!is_dir(base_path('vendor'))) {
            throw new \Exception("Composer install gagal dijalankan!");
        } else {
            echo "Instalisasi selesai!";
        }
    }

    public function argument_parse($args)
    {
        $first = strtolower($this->getArgsStarts($args));
        switch ($first) {
            case 'help':
                echo <<<Help
                Selamat datang di ASMVC CLI.
                Beberapa perintah yang dapat anda gunakan:

                serve | Memulai ASMVC Development Server
                install:boostrap | Install and use bootstrap with asset()
                create:controller {controller} | Membuat Controller
                create:model {model} | Membuat model
                create:middleware {middleware} | Membuat Middleware
                create:test {test} | Membuat Tests
                run:tests {test?} | Menjalankan Test
                reset:router | Menganti file router dengan yang baru
                cleanup | Membersihkan asmvc ke keandaan awal. (Controller, Models, Middleware, dan View akan hilang).
                version | Menampilkan versi ASMVC.

                Help;
                break;

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

            case 'version':
                echo 'ASMVC Version ' . ASMVC_VERSION . ' ' . ASMVC_STATE . PHP_EOL;
                break;

            case 'create:controller':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    $data = <<<data
                    <?php

                    namespace Albet\Ppob\Controllers;
                    
                    use Albet\Ppob\Core\Requests;
                    
                    class {$try} extends BaseController
                    {
                        //Your logic
                    }                    
                    data;
                    file_put_contents(__DIR__ . "/../Controllers/{$try}.php", $data);
                }
                break;

            case 'create:model':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    $data = <<<data
                    <?php

                    namespace Albet\Ppob\Models;

                    class {$try} extends BaseModel
                    {
                        //Your models logic
                    }
                    data;
                    file_put_contents(__DIR__ . "/../Models/{$try}.php", $data);
                }
                break;

            case 'create:middleware':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    $data = <<<data
                        <?php

                        namespace Albet\Ppob\Middleware;

                        use Albet\Ppob\Core\BaseMiddleware;

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

            case 'reset:router':
                $this->resetRouter();
                break;

            case 'cleanup':
                $ask = $this->ask('Apakah anda yakin [Y/n]');
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
                    $views = array_diff(scandir($views_path), ['..', '.', '404.php', 'home.php']);
                    $public = array_diff(scandir(public_path()), ['.', '..', '.gitignore']);
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

            case 'serve':
                if (!function_exists('exec')) {
                    throw new \Exception("Exec() tidak terdeteksi. Harap aktifkan di php.ini");
                }
                echo "ASMVC Development Server Start... (http://localhost:9090)\n";
                exec('php -S localhost:9090');
                break;

            case 'create:test':
                $try = $this->next_arguments($args, 1);
                if ($try) {
                    if (str_contains('Test', $try)) {
                        $data = <<<data
                        <?php
    
                        namespace Albet\Ppob\Tests;
    
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

                        namespace Albet\Ppob\Tests;

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

            default:
                echo 'Command not found. Please run "php asmvc help"' . PHP_EOL;
                break;
        }
    }
}
