<?php

namespace App\Asmvc\Core;

use App\Asmvc\Core\Logger\Logger;
use App\Asmvc\Core\Middleware\Middleware;
use Closure;

class Route
{
    private static array $routes = [];
    private static bool $pagenotfound = false;
    private static bool $ASMVC_LOCAL_URL = false;
    private static string $previous;

    public static function __callStatic($method, $parameters)
    {
        if ($method === "boot") {
            $path = __DIR__ . "/../Routes/url.php";
            if (!file_exists($path)) {
                file_put_contents($path, <<<RouteFile
                <?php

                use App\Asmvc\Core\Route;
                use App\Asmvc\Controllers\HomeController;

                Route::add('/', [HomeController::class, 'index']);
                
                RouteFile);
                Logger::info("Crafted: url.php", [self::class]);
            }
            require_once $path;
        }
    }

    private static function parseParameter(string $path): string
    {
        $spliited = explode('/', $path);
        $result = array_map(function ($item): string {
            if (str_starts_with($item, ":")) {
                return "([0-9a-zA-Z]*)";
            }
            return $item;
        }, $spliited);

        return implode("/", $result);
    }

    /**
     * Add routing to the array
     * @param array $controllerandmethod
     * @param array $http_method_or_middleware
     */
    public static function add(string $path, array|Closure $controllerandmethod, array|string|Middleware ...$http_method_or_middleware)
    {
        $http_method = 'GET';
        $middleware = null;
        if ($http_method_or_middleware != []) {
            if (count($http_method_or_middleware) > 1) {
                $http_method = $http_method_or_middleware[0];
                $middleware = $http_method_or_middleware[1];
            } elseif (count($http_method_or_middleware) > 3) {
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
     * @param array $http_method_or_middleware.
     */
    public static function view(string $path, array|string $view, array|string|Middleware ...$http_method_or_middleware)
    {
        $http_method = 'GET';
        $middleware = null;
        if ($http_method_or_middleware != []) {
            if (count($http_method_or_middleware) > 1) {
                $http_method = $http_method_or_middleware[0];
                $middleware = $http_method_or_middleware[1];
            } elseif (count($http_method_or_middleware) > 3) {
                throw new \Exception("Max arguments for include_view() only allowed 4");
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
        $array['method'] = is_array($view) ? ['view' => $view[0], 'data' => $view[1]] : $view;
        self::$routes[] = $array;
    }

    /**
     * Get ASMVC_LOCAL_URL value
     */
    public static function getAsmvcUrlLocal(): string
    {
        return self::$ASMVC_LOCAL_URL;
    }

    /**
     * Register user previously visit url
     * @return void|string
     */
    public static function registerPrevious(?string $route = null, bool $get = false)
    {
        if (!isset($_SESSION['_previousRoute'])) {
            $_SESSION['_previousRoute'] = [getCurrentUrl()];
        }

        if ($route) {
            $_SESSION['_previousRoute'] = $route;
        }

        $_SESSION['_previousRoute'][] = getCurrentUrl();
        if ((is_countable($_SESSION['_previousRoute']) ? count($_SESSION['_previousRoute']) : 0) > 1) {
            $getPrevious = $_SESSION['_previousRoute'][array_key_last($_SESSION['_previousRoute']) - 1];
        } else {
            $getPrevious = $_SESSION['_previousRoute'][array_key_first($_SESSION['_previousRoute'])];
        }

        if ((is_countable($_SESSION['_previousRoute']) ? count($_SESSION['_previousRoute']) : 0) > 4) {
            for ($i = 0; $i < 5; $i++) {
                unset($_SESSION['_previousRoute'][$i]);
            }
        }

        if ($get) {
            return $getPrevious;
        }
        self::$previous = $getPrevious;
    }

    public static function getPrevious(): string
    {
        return self::$previous;
    }

    /**
     * Run the routing
     * @return returnErrorPage
     */
    public static function triggerRouter(): mixed
    {
        $server = str_contains($_SERVER['REQUEST_URI'], '?') ? strtok($_SERVER['REQUEST_URI'], '?') : $_SERVER['REQUEST_URI'];
        $exploded = explode('\\', realpath('../'));
        $match = $exploded[array_key_last($exploded)] . '/public/index.php';

        if (str_contains($server, $match)) {
            $server = explode('/', getStringAfter('index.php', $server));
            array_shift($server);
            $server = '/' . implode('/', $server);
            // Define where your url start with.
            self::$ASMVC_LOCAL_URL =  $exploded[array_key_last($exploded)];
        }
        if (self::$routes === []) {
            self::$pagenotfound = true;
        }
        foreach (self::$routes as $route) {
            $pattern = "#^{$route['path']}$#";
            if ($server != $route['path']) {
                self::$pagenotfound = true;
            }

            if (preg_match($pattern, $server, $variables)) {
                array_shift($variables);
                if ($_SERVER['REQUEST_METHOD'] != $route['http_method']) {
                    throw new \Exception("Request {$_SERVER['REQUEST_METHOD']} is not support for this url. Instead use {$route['http_method']}!");
                } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (!csrf()->validateCsrf()) {
                        returnErrorPage(500, "Csrf Not Valid.");
                    };
                }
                self::registerPrevious();
                if (!is_null($route['middleware'])) {
                    $middleware = new $route['middleware'];
                    $middleware->middleware();
                }
                if ($route['controller'] == 'view') {
                    if (isset($route['method']['data'])) {
                        return include_view($route['method']['view'], $route['method']['data']);
                    } else {
                        return include_view($route['method']);
                    }
                } else if ($route['controller'] == 'inline') {
                    return call_user_func_array($route['method'], $variables);
                } else {
                    $method = $route['method'];
                    $resolver = new DependencyResolver;
                    return $resolver->methodResolver($route['controller'], $method, ...$variables);
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
                return returnErrorPage(404);
            }
        }
    }
}
