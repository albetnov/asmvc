<?php

namespace Albet\Ppob\Core;

class Flash
{
    public static function flash($name, $message)
    {
        $_SESSION[$name] = $message;
    }

    public static function checkFlash($name)
    {
        if (!isset($_SESSION[$name])) {
            return false;
        } else {
            return true;
        }
    }

    public static function catchFlash($name)
    {
        if (!isset($_SESSION[$name])) {
            return;
        }
        $string = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $string;
    }
}
