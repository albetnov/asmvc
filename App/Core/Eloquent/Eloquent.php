<?php

namespace App\Asmvc\Core\Eloquent;

use App\Asmvc\Core\Eloquent\EloquentDB;
use App\Asmvc\Core\Eloquent\ModelDriverException;
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
        $env = provider_config()['model'];
        if ($env != 'eloquent') {
            throw new ModelDriverException();
        }
        $this->db = new EloquentDB;
    }
}
