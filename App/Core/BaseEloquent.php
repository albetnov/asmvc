<?php

namespace Albet\Asmvc\Core;

use Illuminate\Database\Eloquent\Model;

class BaseEloquent extends Model
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
            throw new \Exception("You can't use eloquent driver since your current driver is: {$env}. Please use it with {$env} way.");
        }
        $this->db = new EloquentDB;
    }
}
