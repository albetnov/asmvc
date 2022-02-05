<?php

/**
 * ASMVC Init System
 * Powered by Composer's PSR4
 */

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Helpers.php';

/**
 * Load DotEnv Library
 */
if (env('APP_ENV') == 'testing') {
    $dotenv = Dotenv::createImmutable(base_path(), '.env.testing');
} else {
    $dotenv = Dotenv::createImmutable(base_path());
}
$dotenv->safeLoad();
