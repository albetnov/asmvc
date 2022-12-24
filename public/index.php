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
SessionManager::make()->runSession();

/**
 * Define Bootstrap const.
 */
define('BS5_CSS', 'css/bootstrap.min.css');
define('BS5_JS', 'js/bootstrap.min.js');
define("TW_CSS", 'styles/output.css');

/**
 * Calling your route
 */
require_once __DIR__ . '/../App/Router/url.php';
Route::triggerRouter();
