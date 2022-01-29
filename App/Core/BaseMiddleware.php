<?php

namespace Albet\Asmvc\Core;

class BaseMiddleware
{
    /**
     * @var CoreModel $db
     */
    protected CoreModel $db;

    /**
     * Consturctor to Query Builder
     */
    public function __construct()
    {
        $this->db = new CoreModel;
    }

    /**
     * Default method of denied.
     */
    public function denied()
    {
        return redirect('/login');
    }
}
