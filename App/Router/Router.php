<?php

namespace Albet\Asmvc\Router;

use Albet\Asmvc\Core\BaseRouter;

class Router extends BaseRouter
{
    /**
     * You can define your routing in here
     * @return void
     */
    public function defineRouter(): void
    {
        /**
         * You can use following method for routing:
         * self::add($urlPath, [Controller::class, 'methodName'], $HttpMethod, $Middleware).
         * self::inline($urlPath, $CallableFunction, $httpMethod, $Middleware).
         * $httpMethod and $middleWare can be optional.
         * It can either be
         * self::add($urlPath, [Controller::class, 'methodName'], $HttpMethod) for Http method only
         * or
         * self::add($urlPath, [Controller::class, 'methodName'], $Middleware) for Middleware only.
         * or both of them.
         * The same rules applies for inline. 
         */

        //Your route
        self::inline('/hello', function () {
            return v_include('cth2');
        });

        self::inline('/hello/world/again', function () {
            echo "Hello World";
        });

        /**
         * Run the routing
         */
        self::triggerRouter();
    }
}
