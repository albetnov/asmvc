<?php

namespace App\Asmvc\Core\Eloquent;

use Illuminate\Database\Capsule\Manager as Eloquent;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class EloquentDB extends Eloquent
{
    /**
     * Construct/Making Eloquent instance
     * @return $this
     */
    public function __construct()
    {
        parent::__construct();
        $this->addConnection($this->getDbConnection());


        $this->setEventDispatcher(new Dispatcher(new Container));

        $this->setAsGlobal();

        $this->bootEloquent();
        return $this;
    }

    /**
     * Get a database Connection
     * @return array{driver: mixed, host: mixed, database: mixed, username: mixed, password: mixed, charset: string, collation: string, prefix: string}
     */
    public function getDbConnection(): array
    {
        return [
            'driver' => env('ELOQUENT_DRIVER', 'mysql'),
            'host' => env('DATABASE_HOST'),
            'database' => env('DATABASE_NAME'),
            'username' => env('DATABASE_USERNAME'),
            'password' => env('DATABASE_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ];
    }
}
