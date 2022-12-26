<?php

namespace Albet\Asmvc\Core\Middleware;

use Closure;

class FluentMiddleware
{
    private ?string $middleware = null;
    private ?array $params = [];

    public function set(string $middleware): self
    {
        $this->middleware = $middleware;
        $this->params = [];
        return $this;
    }

    public function parameters(Closure $fn): self
    {
        $this->params = $fn(new FluentParamParser())->done();
        return $this;
    }

    public function parse()
    {
        if (!$this->middleware) {
            throw new InvalidMiddlewareArgument();
        }

        return [$this->middleware, $this->params];
    }
}
