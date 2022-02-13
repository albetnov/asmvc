<?php

namespace Albet\Asmvc\Core;

use ParagonIE\AntiCSRF\AntiCSRF;

class CsrfGenerator
{
    /**
     * Generate a csrf
     */
    public function generateCsrf()
    {
        if (!isset($_SESSION['token'])) {
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
            $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
            if (!$token || $token !== $_SESSION['token']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Echo a csrf field html
     * @param string $route
     * @return string
     */
    public function field($route = null)
    {
        if (Config::csrfDriver() == 'paragonie') {
            if ($route == null) {
                return new \Exception("Lock to must exist.");
            }
            $csrf = new AntiCSRF();
            return $csrf->insertToken(url($route), false);
        }
        return '<input name="token" type="hidden" value="' . $_SESSION['token'] . '" />';
    }
}
