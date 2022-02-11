<?php

namespace Albet\Asmvc\Core;

use Illuminate\Database\Capsule\Manager as Eloquent;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class EloquentDB extends Eloquent
{
    public function __construct()
    {
        $env = env('APP_MODELS_DRIVER', 'asmvc');
        if ($env != 'eloquent') {
            throw new \Exception("You can't use eloquent driver since your current driver is: {$env}. Please use it with {$env} way.");
        }
        parent::__construct();
        $this->addConnection([
            'driver' => env('ELOQUENT_DRIVER', 'mysql'),
            'host' => env('DATABASE_HOST'),
            'database' => env('DATABASE_NAME'),
            'username' => env('DATABASE_USERNAME'),
            'password' => env('DATABASE_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);


        $this->setEventDispatcher(new Dispatcher(new Container));

        $this->setAsGlobal();

        $this->bootEloquent();
        return $this;
    }
}
