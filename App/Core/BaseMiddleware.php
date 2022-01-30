<?php

namespace Albet\Asmvc\Core;

class BaseMiddleware
{
    /**
     * @var Database $db
     */
    protected Database $db;

    /**
     * Consturctor to Query Builder
     */
    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Default method of denied.
     */
    public function denied()
    {
        return redirect('/login');
    }
}
