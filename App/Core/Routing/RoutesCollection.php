<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\DependencyResolver;

class RoutesCollection
{
    private array $routes = [];

    public function add(string $path, array $handler, ?string $method = 'GET', ?string $middleware = null)
    {
        $this->routes[] = [
            'path' => $path,
            'handler' => function ($args) use ($middleware, $handler, $method) {
                if ($middleware) {
                    if (!class_exists($middleware)) {
                        throw new MiddlewareNotFoundException($middleware);
                    }

                    $middlewareClass = new $middleware;
                    $middlewareClass->middleware();
                }

                $csrfMethod = ['POST', 'PUT', 'PATCH', 'DELETE'];

                if (in_array($method, $csrfMethod)) {
                    if (!csrf()->validateCsrf()) {
                        ReturnError(500, "CSRF validation failed");
                    }
                }

                $resolver = new DependencyResolver();
                $resolver->methodResolver($handler[0], $handler[1], ...$args);
            },
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
