<?php

namespace App\Asmvc\Core\Console;

use Symfony\Component\Console\Input\InputArgument;

class FluentParamBuilder
{
    private ?string $name = null;
    private ?string $default = null;
    private int $inputType = InputArgument::REQUIRED;
    private ?string $desc = null;

    public function __call($method, $parameters)
    {
        if ($method === "parse") return $this->parse();
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDefault(string $default): self
    {
        $this->default = $default;
        return $this;
    }

    public function setInputTypeOptional(): self
    {
        $this->inputType = InputArgument::OPTIONAL;
        return $this;
    }

    public function setInputTypeRequired(): self
    {
        $this->inputType = InputArgument::REQUIRED;
        return $this;
    }

    public function setDesc(string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    private function parse(): array
    {
        if (!$this->name || !$this->desc) {
            throw new InvalidCommandArgumentException();
        }

        return [
            $this->name => [
                'desc' => $this->desc,
                'type' => $this->inputType,
                'default' => $this->default,
            ]
        ];
    }
}
