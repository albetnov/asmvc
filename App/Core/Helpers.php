<?php

use Albet\Asmvc\Core\Connection;
use Albet\Asmvc\Core\CsrfGenerator;
use Albet\Asmvc\Core\Flash;
use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Validator;
use Albet\Asmvc\Core\Views;

/**
 * ASMVC Version and State
 */
define('ASMVC_VERSION', '0.8');
define('ASMVC_STATE', 'Dev');

/**
 * Function to access Requests Class immediately.
 * @return Requests
 */
function request()
{
    return new Requests;
}

/**
 * Function to access CsrfGenerator Class immediately.
 * @return CsrfGenerator
 */
function csrf()
{
    return new CsrfGenerator;
}

/**
 * Function to access CsrfGenerator's field method immediately.
 * @return CsrfGenerator
 */
function csrf_field()
{
    return (new CsrfGenerator)->field();
}

/**
 * Function to get a string after specific character.
 * @param string $char, $string
 */
function getStringAfter($char, $string)
{
    return substr($string, strpos($string, $char) + 1);
}

/**
 * Function to change '.' to '/'.
 * @param string $text
 */
function dotSupport($text)
{
    if (!str_contains($text, '.')) {
        return $text; // mengemembalikkan text karena tidak ada '.'
    }
    return str_replace('.', '/', $text);
}

/**
 * Function to include a view
 * @param string $view, array $data
 */
function v_include($view, $data = [])
{
    $final = dotSupport($view);
    extract($data);
    include __DIR__ . '/../Views/' . $final . ".php";
}

/**
 * Function to access PDO immediately
 * @param string $query
 */
function rawDB($query)
{
    $call_pdo = (new Connection)->getConnection();
    $query = $call_pdo->query($query);
    $query->execute();
    return $query->fetchAll();
}

/**
 * Function to do var_dump then die.
 * @param $dump
 */
function vdd(...$dump)
{
    echo "<pre>" . var_dump($dump) . "</pre>";
    exit();
}

/**
 * Function to get if server is running on HTTPS or HTTP.
 */
function get_http_protocol()
{
    if (!empty($_SERVER['HTTPS'])) {
        return "https";
    } else {
        return "http";
    }
}

/**
 * Function to access public folder.
 * @param string $path
 * @return string
 */
function public_path($path = null)
{
    if ($path) {
        return __DIR__ . '/../../public/' . $path;
    }
    return __DIR__ . '/../../public/';
}

/**
 * Function to access base path of project
 * @param string $path
 * @return string
 */
function base_path($path = null)
{
    if ($path) {
        return __DIR__ . '/../../' . $path;
    }
    return __DIR__ . '/../../';
}

/**
 * Function to access base url of project
 * @param boolean $portOnly
 * @return string
 */
function base_url($portOnly = false)
{
    if ($portOnly) {
        return $_SERVER['SERVER_PORT'];
    }
    $port = isset($_SERVER['SERVER_PORT']) ? ':' . $_SERVER['SERVER_PORT'] : '';
    return $_SERVER['SERVER_NAME'] . $port;
}

/**
 * Function to access base url of project
 * @param string $url
 * @return string
 */
function url($url = null)
{
    if (!is_null($url)) {
        return get_http_protocol() . '://' . base_url() . $url;
    }
    return get_http_protocol() . '://' . base_url();
}

/**
 * Function to access public folder
 * @param string $asset
 * @return string
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
 * Function to redirect an user to specific location
 */
function redirect($to)
{
    header("location:$to");
    exit();
}

/**
 * Function to tell developer if there's a method being chained more than one.
 * @param $mark, string $method, string $custom_msg
 * @throws Exception
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

/**
 * Function to set old to a field
 * @param string $field
 */
function set_old($field)
{
    $store = request()->input($field);
    $_SESSION['old'][$field] = $store;
}

/**
 * Function to get old value of field.
 * @param string $field_name, $data
 * @return string
 */
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

/**
 * Function to flush entire session old if there's no validation error
 */
function flush_old()
{
    unset($_SESSION['old']);
}

/**
 * Function to access Validator's checkError method immediately
 * @param string $field
 * @return Validator
 */
function checkError($field)
{
    return (new Validator)::checkError($field);
}

/**
 * Function to access Validator's validMsg method immediately
 * @param string $field
 * @return Validator
 */
function validateMsg($field)
{
    return (new Validator)::validMsg($field);
}

/**
 * Function to access Views class immediately
 * @return Views
 */
function view()
{
    return new Views;
}

/**
 * Function to return javascript history -1.
 * @param boolean $jsonly
 * @return string
 */
function back($jsonly = false)
{
    if ($jsonly) {
        return "history.go(-1)";
    }
    return "javascript://history.go(-1)";
}

/**
 * Function to access Requests's currentURL method.
 */
function GetCurrentUrl()
{
    return request()->currentURL();
}

/**
 * Function to return a error view and kill the app
 * @param int $num
 */
function ReturnError($num)
{
    if (is_dir(__DIR__ . '/../Views/Errors')) {
        require_once __DIR__ . '/../Views/Errors/' . $num . '.php';
        exit();
    }
    require_once __DIR__ . '/Errors/' . $num . '.php';
    exit();
}
