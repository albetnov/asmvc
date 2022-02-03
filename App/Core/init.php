<?php

/**
 * ASMVC Init System
 * Powered by Composer's PSR4
 */
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Helpers.php';

/**
 * Load DotEnv Library
 */
$dotenv = Dotenv\DotEnv::createImmutable(base_path());
$dotenv->safeLoad();
