<?php

use Albet\Asmvc\Controllers\HomeController;
use Albet\Asmvc\Core\Route;
use ParagonIE\AntiCSRF\AntiCSRF;

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
Route::add('/', [HomeController::class, 'index']);
Route::view('/tes', ['example', [
    'title' => 'Uji Coba',
    'content' => 'Nyoba aja'
]]);
