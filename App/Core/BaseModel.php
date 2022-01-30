<?php

namespace Albet\Asmvc\Core;

class BaseModel
{
    /**
     * @var Database $db, string $table
     */
    protected Database $db;
    protected $table;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->db = new Database;
        if ($this->table) {
            $this->db->defineTable($this->table);
        }
    }

    public function db()
    {
        return $this->db;
    }
}
