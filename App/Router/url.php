<?php

use Albet\Asmvc\Core\Route;

/**
 * You can use following method for routing:
 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod, $Middleware).
 * Route::inline($urlPath, $CallableFunction, $httpMethod, $Middleware).
 * Route::view($path, [$view, $data], $httpMethod, $Middleware). Note: Array is not necessary if you not using $data.
 * $httpMethod and $middleWare can be optional.
 * It can either be
 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod) for Http method only
 * or
 * Route::add($urlPath, [Controller::class, 'methodName'], $Middleware) for Middleware only.
 * or both of them.
 * The same rules applies for inline and view. 
 */


//Your route
Route::inline('/test', function () {
    $_SESSION['logged'] = true;
    echo "done";
});

Route::inline('/test2', function () {
    if (!$_SESSION['logged']) {
        echo "no access";
    } else {
        echo "granted";
    }
});
