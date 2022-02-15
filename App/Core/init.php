<?php

/**
 * ASMVC Init System
 * Powered by Composer's PSR4
 */

use Albet\Asmvc\Core\Config;
use Albet\Asmvc\Core\EloquentDB;
use Dotenv\Dotenv;

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
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    } else {
        $whoops->pushHandler(function ($e) {
            ReturnError(500);
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
