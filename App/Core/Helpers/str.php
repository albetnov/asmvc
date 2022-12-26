<?php

if (!function_exists('getStringAfter')) {
    /**
     * Function to get a string after specific character.
     * @param string $char
     * @param string $string
     * @return string
     */
    function getStringAfter(string $char, string $string): string
    {
        return substr($string, strpos($string, $char) + 1);
    }
}

if (!function_exists('dotSupport')) {
    /**
     * Function to change '.' to '/'.
     * @param string $text
     * @return string
     */
    function dotSupport(string $text): string
    {
        if (!str_contains($text, '.')) {
            return $text; // mengemembalikkan text karena tidak ada '.'
        }
        return str_replace('.', '/', $text);
    }
}

if (!function_exists('getStringBefore')) {
    /**
     * Function to get a string before specific character
     * @param string $char
     * @param string $string
     * @return string
     */
    function getStringBefore(string $char, string $string): string
    {
        $arr = explode($char, $string, 2);
        return $arr[0];
    }
}

if (!function_exists('getStringBetween')) {
    /**
     * Function to get a string between characters
     * @param string $string 
     * @param string $start 
     * @param string $end
     * @return string
     */
    function getStringBetween(string $string, string $start, string $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

if (!function_exists('mkPass')) {
    /**
     * Encrypt a string to given constant.
     * @param $constant
     * @param string $string
     * @return string
     */
    function mkPass(string $constant, string $string): string
    {
        return password_hash($string, $constant);
    }
}

if (!function_exists('comparePass')) {
    /**
     * Compare an encryption.
     * @param string $first
     * @param string $second
     * @return bool
     */
    function comparePass(string $first, string $second): bool
    {
        return password_verify($first, $second);
    }
}
