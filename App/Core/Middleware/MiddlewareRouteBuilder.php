<?php

namespace App\Asmvc\Core\Middleware;

use App\Asmvc\Core\Exceptions\ArrayIsNotAssiactiveException;
use App\Asmvc\Core\Routing\MiddlewareNotFoundException;

class MiddlewareRouteBuilder
{
    private array $middlewares = [];

    public function __call($method, $parameters)
    {
        if ($method === "parse") {
            return $this->parse();
        }
    }

    public function put(string $middleware, ?array $bind = []): self
    {
        if ((array) $bind !== [] && !isAssociativeArray($bind)) {
            throw new ArrayIsNotAssiactiveException();
        }

        if (!class_exists($middleware)) {
            throw new MiddlewareNotFoundException($middleware);
        }

        if (!(str_contains($middleware, 'App\Asmvc\Middleware'))) {
            throw new InvalidMiddlewareArgument();
        }

        $this->middlewares[] = (object) [
            'class' => $middleware,
            'parameters' => $bind
        ];

        return $this;
    }

    private function parse()
    {
        if (count($this->middlewares) === 1) {
            return $this->middlewares[0];
        }

        return $this->middlewares;
    }
}
