<?php

/**
 * Bootstrap the application
 */
require_once __DIR__ . '/../App/Core/bootstrap.php';

use App\Asmvc\Core\SessionManager;

/**
 * Generate a session
 */
SessionManager::make()->runSession();

/**
 * Define FrontEnd libraries const for easier integration.
 */
define('BS5_CSS', 'css/bootstrap.min.css');
define('BS5_JS', 'js/bootstrap.min.js');
define("TW_CSS", 'styles/output.css');

/**
 * Call and map your routes
 */
bootRoutes();
