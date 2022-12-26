<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\Containers\Container;
use Albet\Asmvc\Core\Middleware\FluentMiddleware;
use Closure;

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

    private function callMiddleware(array $middlewareClass)
    {
        if (!class_exists($middlewareClass[0])) {
            throw new MiddlewareNotFoundException($middlewareClass[0]);
        }
        $middleware = Container::fullfil($middlewareClass[0]);
        $middleware->middleware($middlewareClass[1]);
    }

    private function checkForMiddleware(?array $middleware = null)
    {
        if ($middleware) {
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

            return include_view($handler, $args);
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
    }

    public function add(string $path, array|string|Closure $handler, ?string $method = 'GET', FluentMiddleware|array $middleware = null)
    {
        if ($this->isView) {
            $this->viewHandler($middleware, $handler);
        } else if ($this->isClosure) {
            $this->closureHandler($method, $middleware, $handler);
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
