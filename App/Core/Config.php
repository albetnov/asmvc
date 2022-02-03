<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Controllers\HomeController;

class Config extends EntryPoint
{
    /**
     * Define your database connection 
     * @return array
     */
    public function defineConnection(): array
    {
        /**
         * You're free to configure this array.
         */
        return [
            'db_host' => 'localhost',
            'db_name' => 'asmvc',
            'db_user' => 'root',
            'db_pass' => ''
        ];
    }

    /**
     * Define which entry point you going to use.
     * @return array
     */
    public function entryPoint()
    {
        /**
         * $this->controller($class, $method, $middlewareclass) if you would like to make your controller as entry point.
         * $this->view($path, $middlewareclasss) if you only need view for your entry point.
         * or
         * $this->view([$path, $data], $middlewareclass) if you need data for the view.
         * Example:
         * $this->controller(HomeController::class, 'index', AdminMiddleware::class);
         */
        return $this->controller(HomeController::class, 'index');
    }
}
