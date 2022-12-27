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
    private string $type;
    private bool $ip = false, $validate = false, $secure = false;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $session = config('session');
        $this->type = $session['type'];
        $this->ip = $session['ip-validation'];
        $this->validate = $session['session-basic-validation'];
        $this->secure = $session['secure'];
    }

    public static function __callStatic($method, $parameters)
    {
        if ($method === "registerPrevious") {
            return self::registerPrevious(...$parameters);
        }
    }

    public static function make(): self
    {
        return new SessionManager();
    }

    private function sessionSetting(): void
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
        if ($this->secure) {
            ini_set('session.cookie_secure', 'On');
        }
    }

    /**
     * Configure default session then run it.
     */
    public function runSession(): void
    {
        // Configuring ini
        $this->sessionSetting();
        if ($this->type == 'redis') {
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

        $this->generateSession();
        if ($this->validate) {
            $this->validateSession();
        }

        if (!isset($_SESSION['_previousRoute']) || !is_array($_SESSION['_previousRoute'])) {
            $_SESSION['_previousRoute'] = []; // register PreviosRoute as array.
        }
    }

    /**
     * Generating a new session.
     */
    public function generateSession(bool $regenerate = false): void
    {
        if ($regenerate) {
            session_destroy();
        }
        session_start();
        session_regenerate_id(true);
        if (!isset($_SESSION['USER_AGENT'])) {
            $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        }
        if ($this->ip || !isset($_SESSION['USER_IP'])) {
            $_SESSION['USER_IP'] = $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Validating user's session.
     */
    private function validateSession(): void
    {
        if ($_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
            $this->generateSession(true);
        }
        if ($this->ip || $_SESSION['USER_IP'] != $_SERVER['REMOTE_ADDR']) {
            $this->generateSession(true);
        }
    }

    /**
     * Put into session.
     * 
     * @param string $name
     * @param mixed $content
     */
    public function put(string $name, mixed $content): void
    {
        $_SESSION[$name] = $content;
    }

    /**
     * Erase a session
     * 
     * @param string $name
     */
    public function erase(string $name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * Regenerate a session.
     */
    public function regenerate(): void
    {
        $this->generateSession(true);
    }

    /**
     * Get a previous url
     */
    public static function back(): ?string
    {
        if (count(session('_previousRoute')) <= 1) {
            return null;
        }

        return session('_previousRoute')[array_key_last(session('_previousRoute')) - 1];
    }

    /**
     * Register a previous url
     */
    private static function registerPrevious(string $url)
    {
        if (count(session('_previousRoute')) > 2) {
            unset(session('_previousRoute')[array_key_last(session('_previousRoute'))]);
        }

        $_SESSION['_previousRoute'][] = $url;
    }
}
