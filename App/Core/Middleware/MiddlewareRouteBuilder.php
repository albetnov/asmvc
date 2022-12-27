<?php

namespace Albet\Asmvc\Core\Middleware;

use Albet\Asmvc\Core\Exceptions\ArrayIsNotAssiactiveException;
use Albet\Asmvc\Core\Routing\MiddlewareNotFoundException;

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
        if (count($bind) > 0 && !isAssociativeArray($bind)) {
            throw new ArrayIsNotAssiactiveException();
        }

        if (!class_exists($middleware)) {
            throw new MiddlewareNotFoundException($middleware);
        }

        if (!(str_contains($middleware, 'Albet\Asmvc\Middleware'))) {
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
