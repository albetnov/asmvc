<?php


/**
 * Function to get a string after specific character.
 * @param string $char
 * @param string $string
 * @return string
 */
function getStringAfter($char, $string)
{
    return substr($string, strpos($string, $char) + 1);
}

/**
 * Function to change '.' to '/'.
 * @param string $text
 * @return string
 */
function dotSupport($text)
{
    if (!str_contains($text, '.')) {
        return $text; // mengemembalikkan text karena tidak ada '.'
    }
    return str_replace('.', '/', $text);
}

/**
 * Function to get a string before specific character
 * @param string $char
 * @param string $string
 * @return string
 */
function getStringBefore($char, $string)
{
    $arr = explode($char, $string, 2);
    return $arr[0];
}

/**
 * Function to get a string between characters
 * @param string $string 
 * @param string $start 
 * @param string $end
 * @return string
 */
function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
