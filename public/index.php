<?php

require_once __DIR__ . '/../App/Core/init.php';

use Albet\Asmvc\Core\Route;
use Albet\Asmvc\Core\SessionManager;

SessionManager::runSession();
csrf()->generateCsrf();
define('BS5_CSS', '');
define('BS5_JS', '');

/**
 * Calling your route
 */
require_once __DIR__ . '/../App/Router/url.php';
Route::triggerRouter();
