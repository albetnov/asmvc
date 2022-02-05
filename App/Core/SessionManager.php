<?php

namespace Albet\Asmvc\Core;

class SessionManager
{
    private static $type;

    public function __construct()
    {
        $this->type = env('SESSION_TYPE', 'redis');
    }

    public static function runSession()
    {
        if (self::$type == 'redis') {
            ini_set('session.save_handler', 'redis');
            $redisHost = env('REDIS_SERVER', '127.0.0.1');
            $redisDb = env('REDIS_DB_NUMBER', 0);
            $redisPort = env('REDIS_PORT', 6379);
            $redisUser = env('REDIS_AUTH_USER');
            $redisPass = env('REDIS_AUTH_PASS');
            if (!empty($redisUser) && !empty($redisPass)) {
                ini_set('session.save_path', "tcp://{$redisHost}:{$redisPort}?database={$redisDb}&auth=[{$redisUser}, {$redisPass}]");
            } else if (empty($redisUser) && !empty($redisPass)) {
                ini_set('session.save_path', "tcp://{$redisHost}:{$redisPort}?database={$redisDb}&auth={$redisPass}");
            } else {
                ini_set('session.save_path', "tcp://{$redisHost}:{$redisPort}?database={$redisDb}");
            }
        } else {
            ini_set('session.name', 'ASMVCSESSID');
        }
        session_start();
    }
}
