<?php

if (!function_exists('config_path')) {
    /**
     * FUnction that return config path.
     */
    function config_path(?string $file = null): string
    {
        if ($file) return __DIR__ . "/../Config/$file";

        return __DIR__ . "/../Config/";
    }
}

if (!function_exists('config')) {
    /**
     * Function to access config file immadiately
     */
    function config(string $fileName): array
    {
        return require_once config_path($fileName . '.php');
    }
}

if (!function_exists('provider_config')) {
    function provider_config(): array
    {
        return config("providers");
    }
}
