<?php

namespace App\Asmvc\Core\Console;

use Closure;

class FluentCommandBuilder
{
    private array $params = [];
    private ?string $name = null;
    private ?string $desc = null;
    private array $aliases = [];
    private ?string $help = null;
    private array $optionalParams = [];

    public function __call($method, $parameters)
    {
        if ($method === "parse") return $this->parse();
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDesc(string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    public function setAliases(string ...$aliases): self
    {
        $this->aliases = $aliases;
        return $this;
    }


    public function setHelp(string $help): self
    {
        $this->help = $help;
        return $this;
    }

    public function addParam(Closure $fn): self
    {
        $fluentParamBuilder = $fn(new FluentParamBuilder);
        $this->params = array_merge($this->params, $fluentParamBuilder->parse());
        return $this;
    }

    public function addOptionalParam(Closure $fn): self
    {
        $fluentOptionalParamBuilder = $fn(new FluentOptionalParamBuilder);
        $this->optionalParams = array_merge($this->optionalParams, $fluentOptionalParamBuilder->parse());
        return $this;
    }

    private function parse(): object
    {
        if (!$this->name) {
            throw new InvalidCommandNameException();
        }

        if (!$this->desc) {
            throw new InvalidCommandDescException();
        }

        return (object) [
            'params' => $this->params,
            'name' => $this->name,
            'desc' => $this->desc,
            'aliases' => $this->aliases,
            'help' => $this->help,
            'optionalParams' => $this->optionalParams
        ];
    }
}
