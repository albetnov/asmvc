<?php

use Albet\Asmvc\Core\Connection;
use Albet\Asmvc\Core\CsrfGenerator;
use Albet\Asmvc\Core\Flash;
use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Validator;
use Albet\Asmvc\Core\Views;

/**
 * Versi dari ASMVC Anda!.
 */
define('ASMVC_VERSION', '0.8');
define('ASMVC_STATE', 'Dev');

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
    $query = $call_pdo->query($query);
    $query->execute();
    return $query->fetchAll();
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
function public_path($path = null)
{
    if ($path) {
        return __DIR__ . '/../../public/' . $path;
    }
    return __DIR__ . '/../../public/';
}

/**
 * Fungsi untuk  mengakses path awal dari project.
 */
function base_path($path = null)
{
    if ($path) {
        return __DIR__ . '/../../' . $path;
    }
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

function url($url = null)
{
    if (!is_null($url)) {
        return get_http_protocol() . '://' . base_url() . $url;
    }
    return get_http_protocol() . '://' . base_url();
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
    $_SESSION['old'][$field] = $store;
}

function old($field_name, $data = null)
{
    if (isset($_SESSION['old'][$field_name])) {
        $return = $_SESSION['old'][$field_name];
        unset($_SESSION['old'][$field_name]);
        return $return;
    } else if (!is_null($data)) {
        return $data;
    }
    return;
}

function flush_old()
{
    unset($_SESSION['old']);
}

function checkError($field)
{
    return (new Validator)::checkError($field);
}

function validateMsg($field)
{
    return (new Validator)::validMsg($field);
}

function view()
{
    return new Views;
}

function back($jsonly = false)
{
    if ($jsonly) {
        return "history.go(-1)";
    }
    return "javascript://history.go(-1)";
}

function GetCurrentUrl()
{
    return get_http_protocol() . '://' . base_url() . $_SERVER['REQUEST_URI'];
}

function ReturnError($num)
{
    if (is_dir(__DIR__ . '/../Views/Errors')) {
        require_once __DIR__ . '/../Views/Errors/' . $num . '.php';
        exit();
    }
    require_once __DIR__ . '/Errors/' . $num . '.php';
    exit();
}
