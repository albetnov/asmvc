<?php

require_once __DIR__ . '/../App/Core/init.php';

use Albet\Asmvc\Core\Route;
use Albet\Asmvc\Core\SessionManager;

SessionManager::runSession();
csrf()->generateCsrf();
define('BS5_CSS', 'css/bootstrap.min.css');
define('BS5_JS', 'js/bootstrap.min.js');

/**
 * Calling your route
 */
require_once __DIR__ . '/../App/Router/url.php';
Route::triggerRouter();
