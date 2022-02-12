<?php

namespace Albet\Asmvc\Core;

abstract class BaseMiddleware
{
    /**
     * @var $db
     */
    protected $db;

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
    public function denied()
    {
        return redirect('/login');
    }

    /**
     * Add an abstact about required baseMiddleware
     */
    abstract function middleware();
}
