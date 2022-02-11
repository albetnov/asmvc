<?php

namespace Albet\Asmvc\Core;

use Illuminate\Database\Eloquent\Model;

class BaseEloquent extends Model
{

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        $env = env('APP_MODELS_DRIVER', 'asmvc');
        if ($env != 'eloquent') {
            throw new \Exception("You can't use eloquent driver since your current driver is: {$env}. Please use it with {$env} way.");
        }
    }
}
