<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Controllers\BaseController;

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
    public static function add($path, $controllerandmethod, ...$http_method_or_middleware)
    {

        self::block($path);
        $http_method = 'GET';
        $middleware = null;
        if ($http_method_or_middleware != []) {
            if (count($http_method_or_middleware) > 1) {
                $http_method = $http_method_or_middleware[0];
                $middleware = $http_method_or_middleware[1];
            } else if (count($http_method_or_middleware) > 3) {
                throw new \Exception("Argumen maksimal add hanya ada 4");
            }
            if (class_exists($http_method_or_middleware[0])) {
                $middleware = $http_method_or_middleware[0];
            } else {
                $http_method = $http_method_or_middleware[0];
            }
        }
        array_push(self::$routes, [
            'path' => $path,
            'controller' => $controllerandmethod[0],
            'method' => $controllerandmethod[1],
            'http_method' => $http_method,
            'middleware' => $middleware
        ]);
    }

    /**
     * Memungkin kan anda untuk menggunakan inline routing.
     */
    public static function inline($path, $inline, ...$http_method_or_middleware)
    {
        self::block($path);
        $http_method = 'GET';
        $middleware = null;
        if ($http_method_or_middleware != []) {
            if (count($http_method_or_middleware) > 1) {
                $http_method = $http_method_or_middleware[0];
                $middleware = $http_method_or_middleware[1];
            } else if (count($http_method_or_middleware) > 3) {
                throw new \Exception("Argumen maksimal add hanya ada 4");
            }
            if (class_exists($http_method_or_middleware[0])) {
                $middleware = $http_method_or_middleware[0];
            } else {
                $http_method = $http_method_or_middleware[0];
            }
        }
        array_push(self::$routes, [
            'path' => $path,
            'controller' => 'inline',
            'method' => $inline,
            'http_method' => $http_method,
            'middleware' => $middleware
        ]);
    }

    /**
     * Anda bisa mengubah settingan default untuk path /. 
     * Saya sendiri menyarankan untuk megubah method dan controller saja ada BaseController.php
     */
    protected static function baseController()
    {
        $base = new BaseController;
        if (!empty($base->defaultMiddleware())) {
            $middleware_path = "Albet\\Asmvc\\Middleware\\{$base->defaultMiddleware()}";
            $middleware = new $middleware_path();
            $middleware->middleware();
        }
        $mainController = "Albet\\Asmvc\\Controllers\\{$base->mainController()}";
        $call_main = new $mainController();
        $method = $base->defaultMethod();
        return $call_main->$method(new Requests);
    }

    /**
     * Method yang digunakan untuk menjalankan routing 
     */
    public static function triggerRouter()
    {
        if (str_contains($_SERVER['REQUEST_URI'], '?')) {
            $server = strtok($_SERVER['REQUEST_URI'], '?');
        } else {
            $server = $_SERVER['REQUEST_URI'];
        }
        if ($server == '/') {
            self::baseController();
        } else {
            if (empty(self::$routes)) {
                self::$pagenotfound = true;
            }
            foreach (self::$routes as $route) {
                if ($server == $route['path']) {
                    if ($_SERVER['REQUEST_METHOD'] != $route['http_method']) {
                        throw new \Exception("Request {$_SERVER['REQUEST_METHOD']} tidak didukung. Harap gunakan {$route['http_method']}!");
                    }
                    if (!is_null($route['middleware'])) {
                        $middleware = new $route['middleware'];
                        $middleware->middleware();
                    }
                    if ($route['controller'] !== 'inline') {
                        $controller = new $route['controller'];
                        $method = $route['method'];
                        $controller->$method(new Requests);
                    } else {
                        call_user_func($route['method']);
                    }
                    exit;
                } else if ($server != $route['path']) {
                    self::$pagenotfound = true;
                }
            }
        }
        if (self::$pagenotfound) {
            require_once __DIR__ . '/../Views/404.php';
            exit();
        }
    }
}
