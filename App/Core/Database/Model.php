<?php

namespace App\Asmvc\Core\Database;

class Model extends Database
{
    protected string $table;

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->table) {
            $this->defineTable($this->table);
        }
    }
}
