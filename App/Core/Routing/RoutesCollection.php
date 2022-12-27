<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Containers\Container;
use App\Asmvc\Core\Logger\Logger;
use App\Asmvc\Core\Middleware\MiddlewareRouteBuilder;
use Closure;
use stdClass;

class RoutesCollection
{
    private array $routes = [];
    private bool $isView = false;
    private bool $isClosure = false;

    public function setAsView(bool $isView = true)
    {
        $this->isView = $isView;
        return $this;
    }

    public function setAsClosure(bool $isClosure = true)
    {
        $this->isClosure = $isClosure;
        return $this;
    }

    private function callMiddleware(stdClass $middlewareClass)
    {
        $middleware = Container::fulfill($middlewareClass->class);
        Logger::info('Middleware executed', ['middleware' => $middlewareClass->class]);
        $middleware->inject($middlewareClass->parameters);
        $middleware->middleware();
    }

    private function checkForMiddleware(?MiddlewareRouteBuilder $middleware = null)
    {
        if ($middleware) {
            $middleware = $middleware->parse();
            if (is_array($middleware)) {
                foreach ($middleware as $item) {
                    $this->callMiddleware($item);
                }
            } else {
                $this->callMiddleware($middleware);
            }
        }
    }

    private function checkForCsrf(string $method)
    {
        $csrfMethod = ['POST', 'PUT', 'PATCH', 'DELETE'];

        if (in_array($method, $csrfMethod)) {
            if (!csrf()->validateCsrf()) {
                returnErrorPage(500, "CSRF validation failed");
            }
        }
    }

    private function viewHandler($middleware, $handler)
    {
        $handler = function ($args) use ($middleware, $handler) {
            $this->checkForMiddleware($middleware);
            return include_view($handler[0], array_merge($handler[1], $args));
        };

        return $handler;
    }

    private function closureHandler($method, $middleware, $handler)
    {
        $handler = function ($args) use ($method, $middleware, $handler) {
            $this->checkForMiddleware($middleware);
            $this->checkForCsrf($method);

            return $handler($args);
        };

        return $handler;
    }

    public function add(string $path, array|string|Closure $handler, ?string $method = 'GET', ?MiddlewareRouteBuilder $middleware = null)
    {
        if ($this->isView) {
            $handler = $this->viewHandler($middleware, $handler);
        } else if ($this->isClosure) {
            $handler = $this->closureHandler($method, $middleware, $handler);
        } else {
            $handler =  function ($args) use ($middleware, $handler, $method) {
                $this->checkForMiddleware($middleware);
                $this->checkForCsrf($method);

                Container::inject([$handler[0], $handler[1]], $args);
            };
        }

        $this->routes[] = [
            'path' => $path,
            'handler' => $handler,
            'httpMethod' => $method,
            'middleware' => $middleware
        ];

        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
