<?php

namespace App\Asmvc\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    static ?MonologLogger $log = null;

    /**
     * Check whenever log has initiated or not.
     */
    private static function checkForLog()
    {
        if (!(config('log')['record_log'])) {
            return true;
        }
        if (!isset(self::$log)) {
            throw new LogNotBootedException();
        }
    }

    /**
     * Initiate the log
     */
    public static function make(): void
    {
        self::$log = new MonologLogger('asmvc');

        $path = cache_path('asmvc.log');

        if (!file_exists($path)) {
            file_put_contents($path, "");
        }

        self::$log->pushHandler(new StreamHandler($path, config('log')['minimum_level']));
    }

    /**
     * Add log with info as it's level
     */
    public static function info(string $message, array $context = []): void
    {
        if (self::checkForLog()) return;
        self::$log->info($message, $context);
    }

    /**
     * Add log with debug as it's level
     */
    public static function debug(string $message, array $context = []): void
    {
        if (self::checkForLog()) return;
        self::$log->debug($message, $context);
    }

    /**
     * Add log with warning as it's level
     */
    public static function warning(string $message, array $context = []): void
    {
        if (self::checkForLog()) return;
        self::$log->warning($message, $context);
    }

    /**
     * Add log with error as it's level
     */
    public static function error(string $message, array $context = []): void
    {
        if (self::checkForLog()) return;
        self::$log->error($message, $context);
    }
}
