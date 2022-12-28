<?php

namespace App\Asmvc\Core\Console;

use App\Asmvc\Core\Console\Contracts\BadgeColor;
use App\Asmvc\Core\Console\Contracts\PromptableValue;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Termwind\ask;
use function Termwind\render;

abstract class Command extends SymfonyCommand
{
    protected string $name = "";
    protected array $aliases = [];
    protected string $desc = "";

    protected function render(string $html): void
    {
        render($html);
    }

    protected function info(string $message): void
    {
        $this->render(<<<html
        <div class="p-10 bg-sky-500 font-bold text-white m-1">{$message}</div>   
        html);
    }

    protected function error(string $message): void
    {
        $this->render(<<<html
        <div class="p-10 bg-rose-500 font-bold text-white m-1">{$message}</div>
        html);
    }

    protected function success(string $message): void
    {
        $this->render(<<<html
            <div class="p-10 bg-lime-500 font-bold text-white m-1">{$message}</div>
        html);
    }

    protected function badgeWarn(string $message): void
    {
        $this->badge($message, "WARN:", BadgeColor::Yellow);
    }

    protected function badgeSuccess(string $message): void
    {
        $this->badge($message, 'SUCCESS:', BadgeColor::Green);
    }

    protected function badgeError(string $message): void
    {
        $this->badge($message, 'ERROR:', BadgeColor::Red);
    }

    protected function badgeInfo(string $message): void
    {
        $this->badge($message, 'INFO:', BadgeColor::Blue);
    }

    protected function badge(string $message, string $badgeTitle, BadgeColor $color = BadgeColor::Green): void
    {
        $this->render(<<<html
            <p><span class="px-3 {$color->value} text-white font-bold uppercase">{$badgeTitle}</span> {$message}</p>
        html);
    }

    protected function ask(string $question, ?array $autoComplete = null, ?string $defaultValue = null): string|bool
    {
        $hasDefaultPrompt = $defaultValue  ? " (Default: $defaultValue)" : "";
        $question = <<<html
            <div>
                <span class="text-white bg-sky-500 mt-1 px-5 py-1 font-bold">{$question} {$hasDefaultPrompt} ?</span><br />
                <span class="font-bold mt-1"><span class="text-sky-300">></span><span class="text-amber-300">></span><span class="text-rose-300 mr-1">></span></span>
            </div>
        html;
        // $answer = ask($question . $hasDefaultPrompt . " ? ", $autoComplete);
        $answer = ask($question, $autoComplete);

        if (!$answer && $defaultValue) return $defaultValue;
        else if (!$answer) return false;
        else return $answer;
    }

    protected function prompt(string $question, PromptableValue $defaultValue = PromptableValue::YES): PromptableValue|bool
    {
        $yes = ['y', 'yes', 1];
        $no = ['n', 'no', 0];
        $possibleAnswer = array_merge($yes, $no);
        $answer = strtolower($this->ask($question, $possibleAnswer, $defaultValue->value === 1 ? "yes" : "no"));

        if (!in_array($answer, $possibleAnswer)) {
            $this->error("Unrecognized answer.");
            return false;
        }

        if (in_array($answer, $no)) {
            return PromptableValue::NO;
        }

        return PromptableValue::YES;
    }

    public function __call($method, $parameters)
    {
        if ($method === "parse") return $this->parse();
    }

    abstract function handler(InputInterface $inputInterface, OutputInterface $outputInterface): int;
    abstract protected function setup(FluentCommandBuilder $builder): FluentCommandBuilder;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->handler($input, $output);
    }

    private function parse(): self
    {
        $builder = $this->setup(new FluentCommandBuilder)->parse();

        $this->setName($builder->name);
        $this->setDescription($builder->desc);
        $this->setAliases($builder->aliases);
        if ($builder->help) {
            $this->setHelp($builder->help);
        }

        foreach ($builder->params as $key => $value) {
            $this->addArgument($key, $value['type'], $value['desc'], $value['default']);
        }

        foreach ($builder->optionalParams as $key => $value) {
            $this->addOption($key, $value['shortcut'], $value['type'], $value['desc'], $value['default']);
        }

        return $this;
    }
}
