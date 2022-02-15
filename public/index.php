<?php

/**
 * Call autoload
 */
require_once __DIR__ . '/../App/Core/init.php';

use Albet\Asmvc\Core\Route;
use Albet\Asmvc\Core\SessionManager;

/**
 * Generate a session
 */
SessionManager::runSession();

/**
 * Generate a csrf
 */
csrf()->generateCsrf();

/**
 * Define Bootstrap const.
 */
define('BS5_CSS', '');
define('BS5_JS', '');

/**
 * Calling your route
 */
require_once __DIR__ . '/../App/Router/url.php';
Route::triggerRouter();
