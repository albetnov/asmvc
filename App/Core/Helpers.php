<?php

//Include csrf Helper
require_once __DIR__ . '/Helpers/csrf.php';
//Include strAlter Helper
require_once __DIR__ . '/Helpers/strAlter.php';
// Include validator Helper
require_once __DIR__ . '/Helpers/validator.php';

// use Albet\Asmvc\Core\Route;
use Albet\Asmvc\Core\Connection;
use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\SessionManager;
use Albet\Asmvc\Core\Views;

/**
 * ASMVC Version and State
 */
define('ASMVC_VERSION', '2.5');
define('ASMVC_STATE', 'development');

/**
 * Function to access Requests Class immediately.
 * @return Requests
 */
function request(): Requests
{
    return new Requests;
}

/**
 * Function to include a view
 */
function view(string $view, array $data = []): mixed
{
    return (new Views)->view($view, $data);
}

/**
 * Function to access PDO immediately
 * @param string $query
 * @return PDO
 */
function rawDB(string $query): array | false
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
function vdd(mixed ...$dump): never
{
    echo "<pre>";
    var_dump($dump);
    echo "</pre>";
    exit();
}

/**
 * Function to get if server is running on HTTPS or HTTP.
 * @return string
 */
function get_http_protocol(): string
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
function public_path(?string $path = null): string
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
function base_path(?string $path = null): string
{
    if ($path) {
        return __DIR__ . '/../../' . $path;
    }
    return __DIR__ . '/../../';
}

/**
 * Function to access base url of project
 * @param bool $portOnly
 * @return string
 */
function base_url(bool $portOnly = false): string
{
    if ($portOnly) {
        return $_SERVER['SERVER_PORT'];
    }
    $port = isset($_SERVER['SERVER_PORT']) ? ':' . $_SERVER['SERVER_PORT'] : '';
    // if (Route::getAsmvcUrlLocal()) {
    //     return $_SERVER['SERVER_NAME'] . $port . '/' . Route::getAsmvcUrlLocal();
    // }
    return $_SERVER['SERVER_NAME'] . $port;
}

/**
 * Function to access base url of project
 * @param string $url
 * @return string
 */
function url(?string $url = null): string
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
function asset(?string $asset = null): string
{
    $assetPath = get_http_protocol() . '://' . base_url() . '/public/';
    if (is_null($asset)) {
        return $assetPath;
    } else {
        return $assetPath . $asset;
    }
}

/**
 * Function to redirect an user to specific location
 */
function redirect(string $to, bool $outside = true): never
{
    if (!$outside) {
        header("location:" . url($to));
        exit;
    }
    header("location:$to");
    exit;
}

/**
 * Function to tell developer if there's a method being chained more than one.
 * @param $mark, string $method, string $custom_msg
 * @throws Exception
 * @return void
 */
function noSelfChained(bool $mark, string $method, ?string $custom_msg = null): never
{
    if ($mark) {
        if (!is_null($custom_msg)) {
            throw new \Exception($custom_msg);
        }
        throw new \Exception("Method {$method} is not allowed to be called more than one.");
    }
}

/**
 * Function to access Views class immediately
 * @return Views
 */
function views(): Views
{
    return new Views;
}

/**
 * Function to return javascript history -1.
 * @param bool $jsonly
 * @return string
 */
function back(bool $jsonly = false): string
{
    if ($jsonly) {
        return "history.go(-1)";
    }
    return (new SessionManager)->back();
}


/**
 * A function to set a previous
 * @param string $route
 * @return string
 */
function setPrevious(string $route): string
{
    return (new SessionManager)->setPrevious($route);
}

/**
 * Function to access Requests's currentURL method.
 * @return string
 */
function GetCurrentUrl(): string
{
    return request()->currentURL();
}

/**
 * Function to return a error view and kill the app
 * @param int $num
 */
function ReturnError(int $num, ?string $message = null)
{
    http_response_code($num);
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
if (!function_exists('env')) {
    function env(string $name, ?string $optional = null): string
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        } else if (!is_null($optional)) {
            return $optional;
        } else {
            return '';
        }
    }
}

/**
 * Function to access SessionManager directly
 * 
 * @param string $name
 * @return mixed
 */
function session(?string $name = null): string | SessionManager
{
    if (!is_null($name)) {
        if (!isset($_SESSION[$name])) return "";
        return $_SESSION[$name];
    }
    return new SessionManager;
}
