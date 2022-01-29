<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Controllers\BaseController;

class BaseRouter
{
    /**
     * @var array $routes, boolean $pagenotfound
     */
    public static $routes = [], $pagenotfound = false;
    private static $ASMVC_LOCAL_URL = false;

    /**
     * Block a path.
     * @param string $path
     * @throws Exception
     */
    private static function block($path)
    {
        if ($path == '/') {
            throw new \Exception("Overriding URL '/' is not recommended. You should configure it on BaseController only.");
        }
    }

    /**
     * Add routing to the array
     * @param string $path, Class|String $controllerandmethod, Class|String $http_method_or_middleware
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
     * Add routing to the array but for anonymous function only.
     * @param string $path, Callable $inline, Class|String $http_method_or_middleware.
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
     * Default settings for url '/'.
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

    public static function getAsmvcUrlLocal()
    {
        return self::$ASMVC_LOCAL_URL;
    }

    /**
     * Run the routing
     * @return returnError
     */
    public static function triggerRouter()
    {
        if (str_contains($_SERVER['REQUEST_URI'], '?')) {
            $server = strtok($_SERVER['REQUEST_URI'], '?');
        } else {
            $server = $_SERVER['REQUEST_URI'];
        }
        $exploded = explode('\\', realpath('../'));
        $match = $exploded[array_key_last($exploded)] . '/public/index.php';
        if ($server == '/' . $exploded[array_key_last($exploded)] . '/') {
            redirect('public/index.php/');
        };

        if (str_contains($server, $match)) {
            $server = explode('/', getStringAfter('/index.php', $server));
            array_shift($server);
            $server = '/' . implode('/', $server);
            // Define where your url start with.
            self::$ASMVC_LOCAL_URL =  $exploded[array_key_last($exploded)];
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
                    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (!csrf()->validateCsrf()) {
                            return ReturnError(500);
                        };
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
            return ReturnError(404);
        }
    }
}
