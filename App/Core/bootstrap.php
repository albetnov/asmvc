<?php

/**
 * ASMVC Bootstrap and Init System
 * Powered by Composer's PSR4
 */

use App\Asmvc\Core\Containers\Container;
use App\Asmvc\Core\Eloquent\EloquentDB;
use App\Asmvc\Core\Exceptions\DetailableException;
use App\Asmvc\Core\Logger\Logger;
use App\Asmvc\Core\Route as OldRouter;
use App\Asmvc\Core\Routing\Route;
use Dotenv\Dotenv;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Load DotEnv Library
 */
if (env('APP_ENV') == 'testing') {
    $dotenv = Dotenv::createImmutable(base_path(), '.env.testing');
} else {
    $dotenv = Dotenv::createImmutable(base_path());
}
$dotenv->safeLoad();

/**
 * Initiate logger
 */
Logger::make();
Logger::info("Application booting", ['src' => 'Core/boostrap.php']);

/**
 * Load Whoops error handler.
 */
if (!defined('ASMVC_CLI_START')) {
    $whoops = new \Whoops\Run;
    if (env('APP_ENV', 'development') != 'production') {
        if (request()->wantsJson()) {
            $whoops->pushHandler(new JsonResponseHandler); // return this is request wants json.
        } else {
            $whoops->pushHandler(new PrettyPageHandler);
        }
    } else {
        $whoops->pushHandler(function ($e): void {
            returnErrorPage(500);
        });
    }
    $whoops->pushHandler(function ($e): void {
        if ($e instanceof DetailableException) {
            Logger::error($e->getMessage(), ['detail' => $e->getDetail(), 'trace' => $e->getTrace()]);
        } else {
            Logger::error($e->getMessage(), $e->getTrace());
        }
    });
    $whoops->register();
}

/**
 * Generate EloquentDB environment.
 */
if (provider_config()['model'] == 'eloquent') {
    new EloquentDB;
}

/**
 * Boot DI Container for auto injecting.
 */
Container::make();

/**
 * Boot route bootstrapper.
 */
if (!function_exists('bootRoutes')) {
    function bootRoutes(): void
    {
        if (provider_config()['router'] === "new") {
            Route::map()->triggerRoute();
        } else {
            OldRouter::boot();
            OldRouter::triggerRouter();
        }
    }
}
