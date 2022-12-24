<?php

namespace Albet\Asmvc\Core;

class BaseModel extends Database
{
    /**
     * @var string $table
     */
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
