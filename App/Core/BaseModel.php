<?php

namespace Albet\Asmvc\Core;

class BaseModel extends Database
{
    /**
     * @var string $table
     */
    protected $table;

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
