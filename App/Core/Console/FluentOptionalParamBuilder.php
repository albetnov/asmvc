<?php

namespace App\Asmvc\Core\Console;

use Symfony\Component\Console\Input\InputOption;

class FluentOptionalParamBuilder
{
    private ?string $name;
    private ?string $default = null;
    private int $inputType = InputOption::VALUE_NONE;
    private ?string $desc;
    private string|array|null $shortcut = null;

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

    public function setShortcut(string ...$shortcut)
    {
        if (is_array($shortcut) && count($shortcut) <= 1) {
            $shortcut = $shortcut[array_key_first($shortcut)];
        }
        $this->shortcut = $shortcut;
        return $this;
    }

    public function setInputTypeNone(): self
    {
        $this->inputType = InputOption::VALUE_NONE;
        return $this;
    }

    public function setInputTypeArray(): self
    {
        $this->inputType = InputOption::VALUE_IS_ARRAY;
        return $this;
    }

    public function setInputTypeNegatable(): self
    {
        $this->inputType = InputOption::VALUE_NEGATABLE;
        return $this;
    }

    public function setInputTypeOptional(): self
    {
        $this->inputType = InputOption::VALUE_OPTIONAL;
        return $this;
    }

    public function setInputTypeRequired(): self
    {
        $this->inputType = InputOption::VALUE_REQUIRED;
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
                'shortcut' => $this->shortcut
            ]
        ];
    }
}
