<?php

namespace Albet\Asmvc\Core;

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
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
        if (!$token || $token !== $_SESSION['token']) {
            return false;
        }
        return true;
    }

    /**
     * Echo a csrf field html
     * @return string
     */
    public function field()
    {
        return '<input name="token" type="hidden" value="' . $_SESSION['token'] . '" />';
    }
}
