<?php

namespace Albet\Ppob\Router;

use Albet\Ppob\Core\BaseRouter;

class Router extends BaseRouter
{
    /**
     * Anda bisa mendefinisikan routing anda disini.
     */
    public function defineRouter(): void
    {
        /**
         * Ada 2 method yang bisa anda gunakan. inline($path, $function, $http_method) dan 
         * add($path, [controller::class, 'method'], $http_method).
         */

        //Your route

        /**
         * Menjalankan routing
         */
        self::triggerRouter();
    }
}   
