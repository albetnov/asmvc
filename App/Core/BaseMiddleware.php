<?php

namespace Albet\Asmvc\Core;

abstract class BaseMiddleware
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
        if (Config::modelDriver() == 'eloquent') {
            $this->db = new EloquentDB;
        } else {
            $this->db = new Database;
        }
    }

    /**
     * Default method of denied.
     */
    public function denied(): void
    {
        return redirect('/login');
    }

    /**
     * Add an abstact about required baseMiddleware
     */
    abstract function middleware(): void;
}
