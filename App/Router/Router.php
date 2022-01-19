<?php

namespace Albet\Ppob\Router;

use Albet\Ppob\Controllers\HomeController;
use Albet\Ppob\Core\BaseRouter;

class Router extends BaseRouter
{
    /**
     * Anda bisa mendefinisikan routing anda disini.
     */
    public function defineRouter(): void
    {
        /**
         * Ada 2 method yang bisa anda gunakan. inline() dan add().
         */
        self::inline('/inline', function () {
            echo "Hello World";
        });
        self::inline('/testhelpers', function() {
            v_include("testing.test", ['title' => 'working']);
        });
        self::add('/testmodel', HomeController::class, 'testModel');

        /**
         * Menjalankan routing
         */
        self::triggerRouter();
    }
}
