<?php

namespace Albet\Asmvc\Core;

class Config
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
     * Define your models driver
     * @return string
     */
    public static function modelDriver()
    {
        return env('APP_MODELS_DRIVER', 'asmvc');
    }

    /**
     * Define your csrf driver
     * @return string
     */
    public static function csrfDriver()
    {
        return env('APP_CSRF_DRIVER', 'asmvc');
    }

    /**
     * Define your templating engine provider
     * @return string
     */
    public static function viewEngine()
    {
        return env('APP_VIEW_ENGINE', 'latte');
    }
}
