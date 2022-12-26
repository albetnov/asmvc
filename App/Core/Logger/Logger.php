<?php

namespace Albet\Asmvc\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    static ?MonologLogger $log = null;

    private static function checkForLog()
    {
        if (!(config('log')['record_log'])) {
            return true;
        }
        if (!isset(self::$log)) {
            throw new LogNotBootedException();
        }
    }

    public static function make()
    {
        self::$log = new MonologLogger('asmvc');

        $path = cache_path('asmvc.log');

        if (!file_exists($path)) {
            file_put_contents($path, "");
        }

        self::$log->pushHandler(new StreamHandler($path, config('log')['minimum_level']));
    }

    public static function info(string $message, array $context = [])
    {
        if (self::checkForLog()) return;
        self::$log->info($message, $context);
    }

    public static function debug(string $message, array $context = [])
    {
        if (self::checkForLog()) return;
        self::$log->debug($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        if (self::checkForLog()) return;
        self::$log->warning($message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        if (self::checkForLog()) return;
        self::$log->error($message, $context);
    }
}
