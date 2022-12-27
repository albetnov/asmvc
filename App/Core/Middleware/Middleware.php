<?php

namespace App\Asmvc\Core\Middleware;

use App\Asmvc\Core\Database\Database;
use App\Asmvc\Core\Eloquent\EloquentDB;

abstract class Middleware
{
    /**
     * @var $params
     */
    protected object $params;

    /**
     * Consturctor to Query Builder
     */
    protected function db()
    {
        if (provider_config()['model'] == 'eloquent') {
            return new EloquentDB;
        } else {
            return new Database;
        }
    }

    /**
     * Inject parameters (override)
     */
    public function inject(array $params): void
    {
        $this->params = (object) $params;
    }

    /**
     * Middleware function to be executed
     */
    abstract function middleware(): void;
}
