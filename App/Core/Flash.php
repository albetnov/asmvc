<?php

namespace App\Asmvc\Core;

class Flash
{
    /**
     * Flash a session
     * @param string $name
     * @param $message
     */
    public static function flash(string $name, string $message): void
    {
        $_SESSION[$name] = $message;
    }

    /**
     * Check if session exist
     * @param string $name
     * @return bool
     */
    public static function checkFlash(string $name): bool
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
     * @return string
     */
    public static function catchFlash(string $name): string
    {
        if (!isset($_SESSION[$name])) {
            return;
        }
        $string = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $string;
    }
}
