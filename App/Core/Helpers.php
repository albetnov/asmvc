<?php

use Albet\Ppob\Core\Connection;
use Albet\Ppob\Core\CsrfGenerator;
use Albet\Ppob\Core\Flash;
use Albet\Ppob\Core\Requests;
use Albet\Ppob\Core\Validator;

/**
 * Function untuk mengakses class request.
 */
function request()
{
    return new Requests;
}

/**
 * Function untuk mengakses class CsrfGenerator.
 */
function csrf()
{
    return new CsrfGenerator;
}

function csrf_field()
{
    return (new CsrfGenerator)->field();
}

/**
 * Fungsi untuk mendapatkan string setelah karakter tertentu
 */
function getStringAfter($char, $string)
{
    return substr($string, strpos($string, $char) + 1);
}

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
function base_url($portOnly = false)
{
    if ($portOnly) {
        return $_SERVER['SERVER_PORT'];
    }
    $port = isset($_SERVER['SERVER_PORT']) ? ':' . $_SERVER['SERVER_PORT'] : '';
    return $_SERVER['SERVER_NAME'] . $port;
}

function url($url)
{
    return get_http_protocol() . '://' . base_url() . $url;
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

function set_old($field)
{
    $store = request()->input($field);
    Flash::flash('old.' . $field, $store);
}

function old($field_name)
{
    return Flash::catchFlash("old.{$field_name}");
}

function validateMsg($field)
{
    return (new Validator)::validMsg($field);
}
