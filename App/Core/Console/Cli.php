<?php

namespace App\Asmvc\Core\Console;

use App\Asmvc\Core\Containers\Container;
use Symfony\Component\Console\Application;

class Cli
{
    private Application $app;

    public function __construct()
    {
        $this->app = new Application();
    }

    private function internalLoader()
    {
        return array_filter(array_diff_key(scandir(__DIR__ . "/Commands/"), ['.', '..']), fn ($item) => str_ends_with($item, ".php"));
    }

    private function userLoader()
    {
        return array_filter(array_diff_key(scandir(__DIR__ . "/../../Commands/"), ['.', '..']), fn ($item) => str_ends_with($item, ".php"));
    }

    public function register(): self
    {
        $internalLoader = collect($this->internalLoader())->map(fn ($item) => 'App\\Asmvc\\Core\\Console\\Commands\\' . explode('.', $item)[0]);
        // Register the internal cores command
        foreach ($internalLoader as $internalCommand) {
            $this->app->add(Container::fulfill($internalCommand)->parse());
        }

        $userLoader = collect($this->userLoader())->map(fn ($item) => 'App\\Asmvc\\Commands\\' . explode('.', $item)[0]);
        // Register user defined command
        foreach ($userLoader as $userCommand) {
            $this->app->add(Container::fulfill($userCommand)->parse());
        }

        return $this;
    }

    public function load(): int
    {
        return $this->app->run();
    }
}
