<?php

require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Albet\Asmvc\Core\EloquentDriver;

if (!env('APP_ENV') != 'testing') {
    throw new \Exception("Your APP ENV settings may invalid.");
}

$dotenv = Dotenv::createImmutable(base_path(), '.env.testing');
if (env('APP_MODELS_DRIVER', 'asmvc') == 'eloquent') {
    EloquentDriver::bootEloquent();
}
