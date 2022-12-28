<?php

namespace App\Asmvc\Core;

class Flash
{
    /**
     * Flash a session
     * @param $message
     */
    public static function flash(string $name, string $message): void
    {
        $_SESSION[$name] = $message;
    }

    /**
     * Check if session exist
     */
    public static function checkFlash(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Catch a session
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
