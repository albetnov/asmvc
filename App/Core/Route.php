<?php

namespace Albet\Asmvc\Core;

use Closure;

class Route
{
    /**
     * @var array $routes
     * @var boolean $pagenotfound
     * @var boolean $ASMVC_LOCAL_URL
     */
    private static array $routes = [];
    private static bool $pagenotfound = false;
    private static bool $ASMVC_LOCAL_URL = false;
    private static string $previous;

    private static function parseParameter(string $path): string
    {
        $spliited = explode('/', $path);
        $result = array_map(function ($item) {
            if (str_starts_with($item, ":")) {
                return "([0-9a-zA-Z]*)";
            }
        }, $spliited);

        return join("/", $result);
    }

    /**
     * Add routing to the array
     * @param string $path
     * @param array $controllerandmethod
     * @param array $http_method_or_middleware
     */
    public static function add(string $path, array|Closure $controllerandmethod, array|string|BaseMiddleware ...$http_method_or_middleware)
    {
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

        $path = self::parseParameter($path);

        $initial = [
            'path' => $path,
            'http_method' => $http_method,
            'middleware' => $middleware
        ];

        if ($controllerandmethod instanceof Closure) {
            $initial['controller'] = 'inline';
            $initial['method'] = $controllerandmethod;
        } else {
            $initial['controller'] = $controllerandmethod[0];
            $initial['method'] = $controllerandmethod[1];
        }

        self::$routes[] = $initial;
    }

    /**
     * Add routing to the array but for views only.
     * @param string $path
     * @param Array|String $view
     * @param array $http_method_or_middleware.
     */
    public static function view(string $path, array|string $view, array|string|BaseMiddleware ...$http_method_or_middleware)
    {
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
     * Get ASMVC_LOCAL_URL value
     * @return string
     */
    public static function getAsmvcUrlLocal(): string
    {
        return self::$ASMVC_LOCAL_URL;
    }

    /**
     * Register user previously visit url
     * @param string $route
     * @param boolean $get
     * @return void|string
     */
    public static function registerPrevious(?string $route = null, bool $get = false)
    {
        if (!isset($_SESSION['_previousUrl'])) {
            $_SESSION['_previousUrl'] = [GetCurrentUrl()];
        }

        if ($route) {
            $_SESSION['_previousUrl'] = $route;
        }

        $_SESSION['_previousUrl'][] = GetCurrentUrl();
        if (sizeof($_SESSION['_previousUrl']) > 1) {
            $getPrevious = $_SESSION['_previousUrl'][array_key_last($_SESSION['_previousUrl']) - 1];
        } else {
            $getPrevious = $_SESSION['_previousUrl'][array_key_first($_SESSION['_previousUrl'])];
        }

        if (sizeof($_SESSION['_previousUrl']) > 4) {
            for ($i = 0; $i < 5; $i++) {
                unset($_SESSION['_previousUrl'][$i]);
            }
        }

        if ($get) {
            return $getPrevious;
        } else {
            self::$previous = $getPrevious;
        }
    }

    public static function getPrevious(): string
    {
        return self::$previous;
    }

    /**
     * Run the routing
     * @return returnError
     */
    public static function triggerRouter(): void
    {
        if (str_contains($_SERVER['REQUEST_URI'], '?')) {
            $server = strtok($_SERVER['REQUEST_URI'], '?');
        } else {
            $server = $_SERVER['REQUEST_URI'];
        }
        $exploded = explode('\\', realpath('../'));
        $match = $exploded[array_key_last($exploded)] . '/public/index.php';

        if (str_contains($server, $match)) {
            $server = explode('/', getStringAfter('index.php', $server));
            array_shift($server);
            $server = '/' . implode('/', $server);
            // Define where your url start with.
            self::$ASMVC_LOCAL_URL =  $exploded[array_key_last($exploded)];
        }
        if (empty(self::$routes)) {
            self::$pagenotfound = true;
        }
        foreach (self::$routes as $route) {
            $pattern = "#^{$route['path']}$#";
            if (preg_match($pattern, $server, $variables)) {
                array_shift($variables);
                if ($_SERVER['REQUEST_METHOD'] != $route['http_method']) {
                    throw new \Exception("Request {$_SERVER['REQUEST_METHOD']} is not support for this url. Instead use {$route['http_method']}!");
                } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (!csrf()->validateCsrf()) {
                        return ReturnError(500);
                    };
                }
                self::registerPrevious();
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
                    return call_user_func_array($route['method'], $variables);
                } else {
                    $pattern = "#^{$route['path']}$#";
                    $method = $route['method'];
                    $resolver = new DependencyResolver;
                    return $resolver->methodResolver($route['controller'], $method, ...$variables);
                }
                exit;
            } else if ($server != $route['path']) {
                self::$pagenotfound = true;
            }
        }
        if (self::$pagenotfound) {
            if (php_sapi_name() == 'cli-server') {
                $file = $_SERVER['REQUEST_URI'];
                $requestPath = explode('/', $_SERVER['REQUEST_URI']);
                $prefetchExt = explode('.', end($requestPath));
                $extension = end($prefetchExt);
                if ($extension === 'css' || $extension === 'js' && file_exists($file)) {
                    header("Content-Type: text/{$extension}");
                    readfile(__DIR__ . '/../..' . $file);
                    exit;
                }
            }
            return ReturnError(404);
        }
    }
}
