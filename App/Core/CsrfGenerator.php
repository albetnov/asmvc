<?php

namespace Albet\Asmvc\Core;

use ParagonIE\AntiCSRF\AntiCSRF;

class CsrfGenerator
{

    /**
     * @var ParagonieIE\AntiCSRF\AntiCSRF
     */
    private static $csrf = null;

    /**
     * Constructor method.
     */
    public function __construct()
    {
        if (Config::csrfDriver() == 'paragonie') {
            if (is_null(self::$csrf)) {
                self::$csrf = new AntiCSRF();
            }
        }
    }

    /**
     * Generate a csrf
     */
    public function generateCsrf()
    {
        if (Config::csrfDriver() != 'paragonie' && !isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Validate the csrf
     * @return boolean
     */
    public function validateCsrf()
    {
        if (Config::csrfDriver() == 'paragonie') {
            $csrf = new AntiCSRF();
            if ($csrf->validateRequest()) {
                return true;
            }
        } else {
            $token = htmlspecialchars(request()->input('token'));
            if (!$token || $token !== $_SESSION['token']) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Echo a csrf field html
     * @param string $route
     * @return string
     */
    public function field($route = null)
    {
        if (Config::csrfDriver() == 'paragonie') {
            if (is_null($route)) {
                return new \Exception("Lock to must exist.");
            }
            return self::$csrf->insertToken($route, false);
        }
        return '<input name="token" type="hidden" value="' . $_SESSION['token'] . '" />';
    }
}
