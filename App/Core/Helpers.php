<?php

use Albet\Ppob\Core\Connection;

/**
 * Function untuk mengubah directory dari "." ke "/".
 */
function dotSupport($text)
{
    if (!str_contains($text, '.')) {
        return $text; // mengemembalikkan text karena tidak ada '.'
    }
    return str_replace('.', '/', $text);
}

/**
 * Function untuk include sebuah view.
 */
function v_include($view, $data = [])
{
    $final = dotSupport($view);
    extract($data);
    include __DIR__ . '/../Views/' . $final . ".php";
}

/**
 * Function untuk mengakses PDO Query secara langsung
 */
function rawDB($query)
{
    $call_pdo = (new Connection)->getConnection();
    $call_pdo->exec($query);
}

/**
 * Function untuk melakukan var_dump dan die.
 */
function vdd(...$dump)
{
    die(var_dump($dump));
}
