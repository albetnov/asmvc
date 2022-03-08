<?php

namespace Albet\Asmvc\Core;

class SessionManager
{
    /**
     * @var string $type
     * @var boolean $ip
     * @var boolean $validate
     * @var boolean $secure
     */
    private static $type, $ip = false, $validate = false, $secure = false;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $session = include __DIR__ . '/../Config/session.php';
        self::$type = $session['type'];
        self::$ip = $session['ip-validation'];
        self::$validate = $session['session-basic-validation'];
        self::$secure = $session['secure'];
    }

    private static function sessionSetting()
    {
        ini_set('session.name', 'ASMVCSESSID');
        ini_set('session.cookie_lifetime', 0);
        ini_set('session.use_cookies', 'On');
        ini_set('session.use_only_cookies', 'On');
        ini_set('session.use_strict_mode', 'On');
        ini_set('session.cookie_httponly', 'On');
        ini_set('session.cookie_samesite', 'Lax');
        ini_set('session.use_trans_sid', "Off");
        ini_set('session.trans_sid_hosts', '[limited hosts]');
        ini_set('session.trans_sid_tags', '[limited tags]');
        ini_set('session.referer_check', base_url());
        ini_set('session.cache_limiter', 'nocache');
        ini_set('session.sid_length', 48);
        ini_set('session.sid_bits_per_character', 6);
        if (self::$secure) {
            ini_set('session.cookie_secure', 'On');
        }
    }

    /**
     * Configure default session then run it.
     */
    public static function runSession()
    {
        // Configuring ini
        self::sessionSetting();
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
        }

        self::generateSession();
        if (self::$validate) {
            self::validateSession();
        }
    }

    /**
     * Generating a new session.
     */
    public static function generateSession($regenerate = false)
    {
        if ($regenerate) {
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
            self::generateSession(true);
        }
        if (self::$ip || $_SESSION['USER_IP'] != $_SERVER['REMOTE_ADDR']) {
            self::generateSession(true);
        }
    }

    /**
     * Put into session.
     * 
     * @param string $name
     * @param mixed $content
     */
    public function put($name, $content)
    {
        $_SESSION[$name] = $content;
    }

    /**
     * Erase a session
     * 
     * @param string $name
     */
    public function erase($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Regenerate a session.
     */
    public function regenerate()
    {
        return self::generateSession(true);
    }

    /**
     * Get a previous url
     */
    public function back()
    {
        return Route::getPrevious();
    }

    /**
     * Register customized previous url
     * @param string $route
     * @return string
     */
    public function setPrevious($route)
    {
        return Route::registerPrevious($route, true);
    }
}
