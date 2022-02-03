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
         * Example:
         * $this->controller(HomeController::class, 'index', AdminMiddleware::class);
         */
        $entry_type = env('ENTRY_TYPE', 'controller');
        $entry_class = env('ENTRY_CLASS', 'HomeController');
        $entry_method = env("ENTRY_METHOD", 'index');
        $entry_middleware = env('ENTRY_MIDDLEWARE');
        if ($entry_type == 'controller') {
            return $this->controller("\\Albet\\Asmvc\\Controllers\\" . $entry_class, $entry_method, $entry_middleware);
        } else if ($entry_type == 'view') {
            if ($entry_method == '') {
                return $this->view($entry_class, $entry_middleware);
            }
            $parsed = [];
            $parse = explode(',', $entry_method);
            foreach ($parse as $parse) {
                $parsing = explode('.', $parse);
                foreach ($parsing as $parsing) {
                    $parsed[getStringBefore('.', $parse)] = $parsing;
                }
            }
            return $this->view([$entry_class, $parsed], $entry_middleware);
        } else {
            throw new \Exception("Inline not supported to be adjusted in .env. Please adjust in manually in Core/Config.php");
        }
    }
}
