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

function get_http_protocol()
{
    if (!empty($_SERVER['HTTPS'])) {
        return "https";
    } else {
        return "http";
    }
}

/**
 * Function untuk mengambil asset
 */
function asset($asset = null)
{
    if (is_null($asset)) {
        return get_http_protocol() . '://' . $_SERVER['SERVER_NAME'] . '/public/';
    } else {
        return get_http_protocol() . '://' . $_SERVER['SERVER_NAME'] . "/public/{$asset}";
    }
}

function public_path()
{
    return __DIR__ . '/../../public/';
}

function base_path()
{
    return __DIR__ . '/../../';
}

function base_url()
{
    return $_SERVER['SERVER_NAME'];
}

function redirect($to)
{
    header("location:$to");
    exit();
}
