<?php

use App\Asmvc\Core\Route;
use App\Asmvc\Controllers\HomeController;

Route::add('/', [HomeController::class, 'index']);
