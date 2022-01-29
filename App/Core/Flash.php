<?php

namespace Albet\Asmvc\Core;

class Flash
{
    /**
     * Flash a session
     * @param string $name
     * @param $message
     */
    public static function flash($name, $message)
    {
        $_SESSION[$name] = $message;
    }

    /**
     * Check if session exist
     * @param string $name
     * @return boolean
     */
    public static function checkFlash($name)
    {
        if (!isset($_SESSION[$name])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Catch a session
     * @param string $name
     */
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
