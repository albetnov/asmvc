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
    private bool $isApi = false;

    public function setAsView(bool $isView = true): self
    {
        $this->isView = $isView;
        return $this;
    }

    public function setAsClosure(bool $isClosure = true): self
    {
        $this->isClosure = $isClosure;
        return $this;
    }

    public function setAsApi(bool $isApi = true): self
    {
        $this->isApi = $isApi;
        return $this;
    }

    private function callMiddleware(stdClass $middlewareClass): void
    {
        $middleware = Container::fulfill($middlewareClass->class);
        Logger::info('Middleware executed', ['middleware' => $middlewareClass->class]);
        $middleware->inject($middlewareClass->parameters);
        $middleware->middleware();
    }

    private function checkForMiddleware(?MiddlewareRouteBuilder $middleware = null): void
    {
        if ($middleware !== null) {
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

    private function checkForCsrf(string $method): void
    {
        $csrfMethod = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (!in_array($method, $csrfMethod)) {
            return;
        }
        if (csrf()->validateCsrf()) {
            return;
        }
        returnErrorPage(500, "CSRF validation failed");
    }

    private function viewHandler(?MiddlewareRouteBuilder $middleware, string|\Closure|array $handler)
    {
        return function ($args) use ($middleware, $handler) {
            $this->checkForMiddleware($middleware);
            return include_view($handler[0], array_merge($handler[1], $args));
        };
    }

    private function closureHandler(string $method, ?MiddlewareRouteBuilder $middleware, string|\Closure|array $handler)
    {
        if ($this->isApi) {
            return function ($args) use ($middleware, $handler) {
                $this->checkForMiddleware($middleware);

                return $handler($args);
            };
        }

        return function ($args) use ($method, $middleware, $handler) {
            $this->checkForMiddleware($middleware);
            $this->checkForCsrf($method);

            return $handler($args);
        };
    }

    private function controllerHandler(string $method, ?MiddlewareRouteBuilder $middleware, \Closure|array $handler)
    {
        if ($this->isApi) {
            return function ($args) use ($middleware, $handler) {
                $this->checkForMiddleware($middleware);

                return Container::inject([$handler[0], $handler[1]], $args);
            };
        }

        return function ($args) use ($middleware, $handler, $method) {
            $this->checkForMiddleware($middleware);
            $this->checkForCsrf($method);

            return Container::inject([$handler[0], $handler[1]], $args);
        };
    }

    public function add(string $path, array|string|Closure $handler, ?string $method = 'GET', ?MiddlewareRouteBuilder $middleware = null)
    {
        if ($this->isView) {
            $handler = $this->viewHandler($middleware, $handler);
        } elseif ($this->isClosure) {
            $handler = $this->closureHandler($method, $middleware, $handler, $this->isApi);
        } else {
            $handler = $this->controllerHandler($method, $middleware, $handler);
        }

        $api = "";

        if ($this->isApi) {
            if (str_starts_with($path, "/")) {
                $api .= "/api";
            } else {
                $api .= "/api/";
            }
        }

        $this->routes[] = [
            'path' => $api . $path,
            'handler' => $handler,
            'httpMethod' => $method,
            'middleware' => $middleware
        ];

        return $this;
    }

    public function clear(): self
    {
        $this->routes = [];
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
