<?php

namespace Albet\Asmvc\Core;

class Config extends EntryPoint
{
    /**
     * Define your database connection 
     * @return array
     */
    public function defineConnection(): array
    {
        /**
         * Please configure this array at (.env) file.
         */
        return [
            'db_host' => env('DATABASE_HOST', 'localhost'),
            'db_name' => env('DATABASE_NAME', 'asmvc'),
            'db_user' => env('DATABASE_USERNAME', 'root'),
            'db_pass' => env('DATABASE_PASSWORD')
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
         * $this->inline($inline, $middleware) if you need callable anonymous function only.
         * $this->fromEnv() will decide which method to use based from your ENV Configuration.
         * Example:
         * $this->controller(HomeController::class, 'index', AdminMiddleware::class);
         */
        return $this->fromEnv();
    }

    public function modelsDriver()
    {
        return env('APP_MODELS_DRIVER', 'asmvc');
    }
}
