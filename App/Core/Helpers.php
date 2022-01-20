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
 * Fungsi untuk mengakses path publik.
 */
function public_path()
{
    return __DIR__ . '/../../public/';
}

/**
 * Fungsi untuk  mengakses path awal dari project.
 */
function base_path()
{
    return __DIR__ . '/../../';
}

/**
 * Fungsi untuk mendapatkan base URL.
 */
function base_url()
{
    return $_SERVER['SERVER_NAME'];
}

/**
 * Function untuk mengambil asset
 */
function asset($asset = null)
{
    if (is_null($asset)) {
        return get_http_protocol() . '://' . base_url() . '/public/';
    } else {
        return get_http_protocol() . '://' . base_url() . "/public/{$asset}";
    }
}

/**
 * Fungsi untuk redirect ke halaman
 */
function redirect($to)
{
    header("location:$to");
    exit();
}

/**
 * Fungsi untuk sistem agar tidak ada method chain yang dipanggil lebih dari 1 kali.
 */
function noSelfChained($mark, $method, $custom_msg = null)
{
    if ($mark) {
        if (!is_null($custom_msg)) {
            throw new \Exception($custom_msg);
        }
        throw new \Exception("Metode {$method} tidak boleh di panggil lebih dari sekali.");
    } else {
        return;
    }
}
