<?php

/**
 * ASMVC Init System
 * Powered by Composer's PSR4
 */

use Albet\Asmvc\Core\Config;
use Albet\Asmvc\Core\EloquentDB;
use Albet\Asmvc\Core\Requests;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Laminas\Diactoros\ServerRequestFactory;

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

if (!function_exists('container')) {
    function container()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions();
        $container = $containerBuilder->build();
        return $container;
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new Requests;
    }
}
