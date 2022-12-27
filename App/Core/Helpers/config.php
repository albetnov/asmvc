<?php

if (!function_exists('config_path')) {
    /**
     * FUnction that return config path.
     */
    function config_path(?string $file = null): string
    {
        if ($file) return __DIR__ . "/../../Config/$file";

        return __DIR__ . "/../../Config/";
    }
}

if (!function_exists('config')) {
    /**
     * Function to access config file immadiately
     */
    function config(string $fileName): array
    {
        $config = require config_path($fileName . '.php');
        return ((array) $config);
    }
}

if (!function_exists('provider_config')) {
    /**
     * Function to access provider config files.
     */
    function provider_config(): array
    {
        return (array) config('providers');
    }
}
