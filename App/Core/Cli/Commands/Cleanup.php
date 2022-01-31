<?php

namespace Albet\Asmvc\Core\Cli\Commands;

use Albet\Asmvc\Core\Cli\BaseCli;
use Albet\Asmvc\Core\Cli\Cleanup as CliCleanup;
use Albet\Asmvc\Core\Cli\ResetIndex;
use Albet\Asmvc\Core\Cli\ResetRouter;

class Cleanup extends BaseCli
{
    use CliCleanup, ResetRouter, ResetIndex;
    protected $command = "cleanup";
    protected $desc = "Clean up ASMVC to fresh install state. (Controller, Models, Middleware, dan View will get deleted).";

    public function register()
    {
        $ask = $this->ask('Are you sure [Y/n]');
        if (strtolower($ask) == 'y') {
            $controller_path = base_path() . 'App/Controllers/';
            $controller_exclude = ['.', '..'];
            if (ASMVC_STATE == 'Dev') {
                $controller_exclude[] = 'HomeController.php';
            }
            $controller = array_diff(scandir($controller_path), $controller_exclude);
            $models_path = base_path() . 'App/Models/';
            $models = array_diff(scandir($models_path), ['.', '..']);
            $middleware_path = base_path() . 'App/Middleware/';
            $middleware = array_diff(scandir($middleware_path), ['.', '..']);
            $views_path = base_path() . 'App/Views/';
            $views = array_diff(scandir($views_path), ['..', '.', 'home.php']);
            $public = array_diff(scandir(public_path()), ['.', '..', '.gitkeep']);
            $tests_path = base_path() . 'App/Tests/';
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
    }
}
