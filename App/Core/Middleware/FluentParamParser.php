<?php

namespace Albet\Asmvc\Core\Middleware;

class FluentParamParser
{
    private $params = [];

    public function __call($method, $parameters)
    {
        if ($method === "done") {
            return $this->done();
        }
    }

    public function addParam(string $name, string $param): self
    {
        if (isset($this->params[$name])) {
            throw new DuplicateParamIdentifier();
        }
        $this->params[$name] = $param;
        return $this;
    }

    private function done()
    {
        return $this->params;
    }
}
