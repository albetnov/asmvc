<?php

namespace Albet\Asmvc\Core;

class Route
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
            throw new \Exception("Overriding URL '/' is not recommended. You should configure it on Core/Config.php only.");
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
                throw new \Exception("Max arguments for add() only allowed 4");
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
                throw new \Exception("Max arguments for inline() only allowed 4");
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
     * Add routing to the array but for views only.
     * @param string $path, Array|String $view, Class|String $http_method_or_middleware.
     */
    public static function view($path, $view, ...$http_method_or_middleware)
    {
        self::block($path);
        $http_method = 'GET';
        $middleware = null;
        if ($http_method_or_middleware != []) {
            if (count($http_method_or_middleware) > 1) {
                $http_method = $http_method_or_middleware[0];
                $middleware = $http_method_or_middleware[1];
            } else if (count($http_method_or_middleware) > 3) {
                throw new \Exception("Max arguments for view() only allowed 4");
            }
            if (class_exists($http_method_or_middleware[0])) {
                $middleware = $http_method_or_middleware[0];
            } else {
                $http_method = $http_method_or_middleware[0];
            }
        }
        $array = [
            'path' => $path,
            'controller' => 'view',
            'http_method' => $http_method,
            'middleware' => $middleware
        ];
        if (is_array($view)) {
            $array['method'] = ['view' => $view[0], 'data' => $view[1]];
        } else {
            $array['method'] = $view;
        }
        array_push(self::$routes, $array);
    }

    /**
     * Default settings for url '/'.
     */
    protected static function mainEntry()
    {
        $config = (new Config)->entryPoint();
        if (!empty($config['middleware'])) {
            $middleware = new $config['middleware'];
            $middleware->middleware();
        }
        if (isset($config['controller'])) {
            $call_main = new $config['controller'];
            $method = $config['method'];
            return $call_main->$method(new Requests);
        } else if (isset($config['path'])) {
            if (isset($config['data'])) {
                return view($config['path'], $config['data']);
            }
            return view($config['path']);
        } else if (isset($config['inline'])) {
            return call_user_func($config['inline']);
        } else {
            throw new \Exception("Invalid entry point!");
        }
    }

    /**
     * Get ASMVC_LOCAL_URL value
     */
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
            self::mainEntry();
        } else {
            if (empty(self::$routes)) {
                self::$pagenotfound = true;
            }
            foreach (self::$routes as $route) {

                if ($server == $route['path']) {
                    if ($_SERVER['REQUEST_METHOD'] != $route['http_method']) {
                        throw new \Exception("Request {$_SERVER['REQUEST_METHOD']} is not support for this url. Instead use {$route['http_method']}!");
                    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (!csrf()->validateCsrf()) {
                            return ReturnError(500);
                        };
                    }
                    if (!is_null($route['middleware'])) {
                        $middleware = new $route['middleware'];
                        $middleware->middleware();
                    }
                    if ($route['controller'] == 'view') {
                        if (isset($route['method']['data'])) {
                            return view($route['method']['view'], $route['method']['data']);
                        } else {
                            return view($route['method']);
                        }
                    } else if ($route['controller'] == 'inline') {
                        return call_user_func($route['method']);
                    } else {
                        $controller = new $route['controller'];
                        $method = $route['method'];
                        return $controller->$method(new Requests);
                    }
                    exit;
                } else if ($server != $route['path']) {
                    self::$pagenotfound = true;
                }
            }
        }
        if (self::$pagenotfound) {
            if (php_sapi_name() == 'cli-server') {
                $file = public_path($_SERVER['REQUEST_URI']);
                $requestPath = explode('/', $_SERVER['REQUEST_URI']);
                $prefetchExt = explode('.', end($requestPath));
                $extension = end($prefetchExt);
                if ($extension != 'css' && $extension != 'js') {
                    ReturnError(500);
                    exit;
                }
                if (file_exists($file)) {
                    header("Content-Type: text/{$extension}");
                    readfile($file);
                    exit;
                }
            }
            return ReturnError(404);
        }
    }
}
