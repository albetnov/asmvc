<?php

/**
 * ASMVC Init System
 * Powered by Composer's PSR4
 */

use Albet\Asmvc\Core\Config;
use Albet\Asmvc\Core\Containers\Container;
use Albet\Asmvc\Core\Eloquent\EloquentDB;
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
        $whoops->pushHandler(function ($e) {
            returnErrorPage(500);
        });
    }
    $whoops->register();
}

/**
 * Generate EloquentDB environment.
 */
if (Config::modelDriver() == 'eloquent') {
    new EloquentDB;
}

/**
 * Boot DI Container for auto injecting.
 */
Container::make();
