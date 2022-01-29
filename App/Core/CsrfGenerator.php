<?php

namespace Albet\Asmvc\Core;

class CsrfGenerator
{
    public function generateCsrf()
    {
        session_start();
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    }

    public function validateCsrf()
    {
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
        if (!$token || $token !== $_SESSION['token']) {
            return false;
        }
        return true;
    }

    public function field()
    {
        return '<input name="token" type="hidden" value="' . $_SESSION['token'] . '" />';
    }
}
