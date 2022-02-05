<?php

//Include csrf Helper
require_once __DIR__ . '/Helpers/csrf.php';
//Include strAlter Helper
require_once __DIR__ . '/Helpers/strAlter.php';
// Include validator Helper
require_once __DIR__ . '/Helpers/validator.php';

use Albet\Asmvc\Core\Route;
use Albet\Asmvc\Core\Connection;
use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Views;

/**
 * ASMVC Version and State
 */
define('ASMVC_VERSION', '1.0');
define('ASMVC_STATE', 'release');

/**
 * Function to access Requests Class immediately.
 * @return Requests
 */
function request()
{
    return new Requests;
}

/**
 * Function to include a view
 * @param string $view, array $data
 */
function view($view, $data = [])
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
    if (Route::getAsmvcUrlLocal()) {
        return $_SERVER['SERVER_NAME'] . $port . '/' . Route::getAsmvcUrlLocal();
    }
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
function redirect($to, $outside = true)
{
    if (!$outside) {
        header("location:" . base_path() . "$to");
        exit;
    }
    header("location:$to");
    exit;
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
        throw new \Exception("Method {$method} is not allowed to be called more than one.");
    } else {
        return;
    }
}

/**
 * Function to access Views class immediately
 * @return Views
 */
function views()
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
function ReturnError($num, $message = null)
{
    if (is_dir(__DIR__ . '/../Views/Errors')) {
        require_once __DIR__ . '/../Views/Errors/' . $num . '.php';
        exit();
    }
    require_once __DIR__ . '/Errors/' . $num . '.php';
    exit();
}

/**
 * Function to get an env, If there's no env you can put
 * optional value.
 * @param string $name
 * @param string $optional
 * @return string
 */
function env($name, $optional = null)
{
    if (isset($_ENV[$name])) {
        return $_ENV[$name];
    } else if (!is_null($optional)) {
        return $optional;
    } else {
        return '';
    }
}
