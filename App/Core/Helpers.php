<?php

// Include csrf Helper
require_once __DIR__ . '/Helpers/csrf.php';
// Include strAlter Helper
require_once __DIR__ . '/Helpers/str.php';
// Include validator Helper
require_once __DIR__ . '/Helpers/validator.php';
// Include config Helper
require_once __DIR__ . '/Helpers/config.php';

// use App\Asmvc\Core\Route;

use App\Asmvc\Core\Database\Connection;
use App\Asmvc\Core\Exceptions\InvalidHttpCodePageException;
use App\Asmvc\Core\Requests;
use App\Asmvc\Core\SessionManager;
use App\Asmvc\Core\Views\Views;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ASMVC Version and State
 */
define('ASMVC_VERSION', '3.0-dev');
define('ASMVC_STATE', 'development');

if (!function_exists('request')) {
    function request(): \App\Asmvc\Core\Requests
    {
        return new Requests;
    }
}


if (!function_exists('include_view')) {
    /**
     * Function to include a view
     */
    function include_view(string $view, array $data = []): mixed
    {
        return (new Views)->include_view($view, $data);
    }
}

if (!function_exists('rawDB')) {

    /**
     * Function to access PDO immediately
     * @return PDO
     */
    function rawDB(string $query): array | false
    {
        $call_pdo = (new Connection)->getConnection();
        $query = $call_pdo->query($query);
        $query->execute();
        return $query->fetchAll();
    }
}

if (!function_exists('get_http_protocol')) {
    /**
     * Function to get if server is running on HTTPS or HTTP.
     */
    function get_http_protocol(): string
    {
        if (!empty($_SERVER['HTTPS'])) {
            return "https";
        }
        return "http";
    }
}

if (!function_exists('public_path')) {
    /**
     * Function to access public folder.
     */
    function public_path(?string $path = null): string
    {
        if ($path) {
            return __DIR__ . '/../../public/' . $path;
        }
        return __DIR__ . '/../../public/';
    }
}

if (!function_exists('base_path')) {
    /**
     * Function to access base path of project
     */
    function base_path(?string $path = null): string
    {
        if ($path) {
            return __DIR__ . '/../../' . $path;
        }
        return __DIR__ . '/../../';
    }
}

if (!function_exists('base_url')) {
    /**
     * Function to access base url of project
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
}

if (!function_exists('url')) {
    /**
     * Function to access base url of project
     */
    function url(?string $url = null): string
    {
        if (!is_null($url)) {
            return get_http_protocol() . '://' . base_url() . $url;
        }
        return get_http_protocol() . '://' . base_url();
    }
}

if (!function_exists('asset')) {
    /**
     * Function to access public folder
     */
    function asset(?string $asset = null): string
    {
        $assetPath = get_http_protocol() . '://' . base_url() . '/public/';
        if (is_null($asset)) {
            return $assetPath;
        }
        return $assetPath . $asset;
    }
}

if (!function_exists('redirect')) {
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
}

if (!function_exists('noSelfChained')) {
    /**
     * Function to tell developer if there's a method being chained more than one.
     * @param $mark, string $method, string $custom_msg
     * @throws Exception
     */
    function noSelfChained(bool $mark, string $method, ?string $custom_msg = null)
    {
        if ($mark) {
            if (!is_null($custom_msg)) {
                throw new \Exception($custom_msg);
            }
            throw new \Exception("Method {$method} is not allowed to be called more than one.");
        }
    }
}

if (!function_exists('views')) {
    /**
     * Function to access Views class immediately
     */
    function views(): Views
    {
        return new Views;
    }
}

if (!function_exists('back')) {
    /**
     * Function to return javascript history -1.
     */
    function back(bool $jsonly = false): ?string
    {
        if ($jsonly) {
            return "history.go(-1)";
        }
        return (new SessionManager)->back();
    }
}

if (!function_exists('getCurrentUrl')) {
    /**
     * Function to access Requests's currentURL method.
     */
    function getCurrentUrl(): string
    {
        return request()->getCurrentUrl();
    }
}

if (!function_exists('returnErrorPage')) {
    /**
     * Function to return a error view and kill the app
     */
    function returnErrorPage(int $code, bool $showPage = true, ?string $message = null)
    {
        http_response_code($code);
        if (!$showPage) return;
        $whitelistCode = ['404', '500'];
        if (!in_array($code, $whitelistCode)) {
            throw new InvalidHttpCodePageException($code, $whitelistCode);
        }
        if (is_dir(__DIR__ . '/../Views/Errors')) {
            require_once __DIR__ . '/../Views/Errors/' . $code . '.php';
            exit();
        }
        require_once __DIR__ . '/Errors/' . $code . '.php';
        exit();
    }
}

if (!function_exists('env')) {
    /**
     * Function to get an env, If there's no env you can put
     * optional value.
     */
    function env(string $name, mixed $default = null): string|bool
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }
        if (!is_null($default)) {
            return $default;
        }

        return false;
    }
}

if (!function_exists('session')) {
    /**
     * Function to access SessionManager directly
     *
     * @return mixed
     */
    function session(?string $name = null): string | array | SessionManager
    {
        if (!is_null($name)) {
            if (!isset($_SESSION[$name])) return "";
            return $_SESSION[$name];
        }
        return new SessionManager;
    }
}

if (!function_exists('cache_path')) {
    /**
     * function to access cache path
     */
    function cache_path(?string $fileName = null)
    {
        $path = __DIR__ . "/../Cache/";

        if ($fileName) {
            $path .= $fileName;
        }

        return $path;
    }
}

if (!function_exists('isAssociativeArray')) {
    /**
     * function to check whenever the array is associative or not
     */
    function isAssociativeArray($array): bool
    {
        return array_keys($array) !== range(0, (is_countable($array) ? count($array) : 0) - 1);
    }
}

if (!function_exists('collect')) {
    /**
     * function to create a new collection
     * based on Doctrine/Collection
     */
    function collect(array $data): \Doctrine\Common\Collections\ArrayCollection
    {
        return new ArrayCollection($data);
    }
}
