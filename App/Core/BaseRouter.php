<?php

namespace Albet\Ppob\Core;

use Albet\Ppob\Controllers\BaseController;

class BaseRouter
{
    /**
     * Definisian variabel routing dan juga halaman tidak ditemukan.
     */
    public static $routes = [], $pagenotfound = false;

    private static function block($path)
    {
        if ($path == '/') {
            throw new \Exception("Overriding '/' URL Path tidak disarankan. Sebaiknya anda mengkonfigurasinya di BaseController saja.");
        }
    }

    /**
     * Menambahkan routing ke dalam array.
     */
    public static function add($path, $controllerandmethod, $http_method = 'GET')
    {
        self::block($path);
        array_push(self::$routes, [
            'path' => $path,
            'controller' => $controllerandmethod[0],
            'method' => $controllerandmethod[1],
            'http_method' => $http_method
        ]);
    }

    /**
     * Memungkin kan anda untuk menggunakan inline routing.
     */
    public static function inline($path, $inline, $http_method = 'GET')
    {
        self::block($path);
        array_push(self::$routes, [
            'path' => $path,
            'controller' => 'inline',
            'method' => $inline,
            'http_method' => $http_method
        ]);
    }

    /**
     * Anda bisa mengubah settingan default untuk path /. 
     * Saya sendiri menyarankan untuk megubah method dan controller saja ada BaseController.php
     */
    protected static function baseController()
    {
        $base = new BaseController;
        $mainController = "Albet\\Ppob\\Controllers\\{$base->mainController()}";
        $call_main = new $mainController();
        $method = $base->defaultMethod();
        return $call_main->$method();
    }

    /**
     * Method yang digunakan untuk menjalankan routing 
     */
    public static function triggerRouter()
    {
        foreach (self::$routes as $route) {
            if ($_SERVER['REQUEST_URI'] == '/') {
                self::baseController();
            } else if ($_SERVER['REQUEST_URI'] == $route['path']) {
                if ($_SERVER['REQUEST_METHOD'] != $route['http_method']) {
                    throw new \Exception("Request {$_SERVER['REQUEST_METHOD']} tidak didukung. Harap gunakan {$route['http_method']}!");
                }
                if ($route['controller'] !== 'inline') {
                    $controller = new $route['controller'];
                    $method = $route['method'];
                    $controller->$method();
                } else {
                    call_user_func($route['method']);
                }
                exit;
            } else if ($_SERVER['REQUEST_URI'] != $route['path']) {
                self::$pagenotfound = true;
            }
        }
        if (self::$pagenotfound) {
            require_once __DIR__ . '/../Views/404.php';
            exit();
        }
    }
}
