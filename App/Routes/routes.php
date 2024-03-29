<?php

/**
 * Welcome to a new Routing Interface.
 * This is a new Routing Interface which can be setted using ENV in ROUTE_DRIVER.
 * The new routing interface use Nikic/FastRoute as it's routing engine.
 * Making the routing of ASMVC blazing fast while providing convenient and readable API interface.
 * 
 * This file must return a anonymous function.
 */

use App\Asmvc\Controllers\HomeController;
use App\Asmvc\Core\Routing\Route;
use App\Asmvc\Core\Middleware\MiddlewareRouteBuilder;
use App\Asmvc\Core\Views\ViewRouteBuilder;

return static function (Route $router, MiddlewareRouteBuilder $mwBuilder): void {
    $router->get('/', [HomeController::class, 'index']);
};
