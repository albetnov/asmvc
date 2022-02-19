<?php

namespace Albet\Asmvc\Core;

class SessionManager
{
    /**
     * @var string $type
     * @var boolean $ip
     */
    private static $type, $ip;

    /**
     * Constructor method
     * @param boolean $ipvalidation
     */
    public function __construct($ipvalidation = true)
    {
        self::$type = env('SESSION_TYPE', 'redis');
        self::$ip = $ipvalidation;
    }

    /**
     * Configure default session then run it.
     * @param string $parameter
     */
    public static function runSession($parameter)
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

        self::generateSession();
        if ($parameter != 'no-validate') {
            self::validateSession();
        }
    }

    /**
     * Generating a new session.
     */
    public static function generateSession($regenerate = false)
    {
        if (isset($_SESSION['USER_AGENT']) || isset($_SESSION['USER_IP']) || $regenerate) {
            session_destroy();
        }
        session_start();
        session_regenerate_id(true);
        if (!isset($_SESSION['USER_AGENT'])) {
            $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        }
        if (self::$ip || !isset($_SESSION['USER_IP'])) {
            $_SESSION['USER_IP'] = $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Validating user's session.
     */
    private static function validateSession()
    {
        if ($_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
            self::generateSession();
        }
        if (self::$ip || $_SESSION['USER_IP'] != $_SERVER['REMOTE_ADDR']) {
            self::generateSession();
        }
    }
}
