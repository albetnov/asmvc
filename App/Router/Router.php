<?php

namespace Albet\Ppob\Router;

use Albet\Ppob\Controllers\HomeController;
use Albet\Ppob\Core\BaseRouter;
use Albet\Ppob\Middleware\LoggedIn;

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
        self::inline('/inline', function () {
            echo "Hello World";
        }, LoggedIn::class);
        self::inline('/testhelpers', function () {
            v_include("testing.test", ['title' => 'working']);
        });
        self::add('/testmodel', [HomeController::class, 'testModel']);
        /**
         * Menjalankan routing
         */
        self::triggerRouter();
    }
}
