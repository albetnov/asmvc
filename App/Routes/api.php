<?php

use App\Asmvc\Core\Middleware\MiddlewareRouteBuilder;
use App\Asmvc\Core\Routing\Route;

/**
 * Welcome to Api Routing
 * 
 * Routes in here are CSRF-free and mapped to /api automatically.
 * Have a try to hit /api/hello below!
 */

return static function (Route $router, MiddlewareRouteBuilder $mwBuilder) {
    $router->get('/hello', function () {
        return ['hello' => 'world'];
    });
};
