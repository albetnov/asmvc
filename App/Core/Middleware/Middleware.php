<?php

namespace Albet\Asmvc\Core\Middleware;

use Albet\Asmvc\Core\Config;
use Albet\Asmvc\Core\Database\Database;
use Albet\Asmvc\Core\Eloquent\EloquentDB;

abstract class Middleware
{
    /**
     * @var $db
     */
    protected Database | EloquentDB $db;

    /**
     * Consturctor to Query Builder
     */
    public function __construct()
    {
        // if (Config::modelDriver() == 'eloquent') {
        //     $this->db = new EloquentDB;
        // } else {
        //     $this->db = new Database;
        // }
    }

    /**
     * Default method of denied.
     */
    public function denied(): void
    {
        redirect('/login');
    }

    /**
     * Add an abstact about required baseMiddleware
     */
    abstract function middleware($params): void;
}
