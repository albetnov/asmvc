<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Core\Eloquent\EloquentDB;
use Albet\Asmvc\Core\Eloquent\ModelDriverException;
use Illuminate\Database\Eloquent\Model;

class Eloquent extends Model
{
    /**
     * Add eloquent query builder
     * @var $db
     * @var string $table
     */
    protected $db, $table;

    /**
     * Constructor method
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $env = Config::modelDriver();
        if ($env != 'eloquent') {
            throw new ModelDriverException();
        }
        $this->db = new EloquentDB;
    }
}
