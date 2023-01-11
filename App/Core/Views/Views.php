<?php

namespace App\Asmvc\Core\Views;

use App\Asmvc\Core\Flash;

class Views
{
    /**
     * @var array $sectionList 
     * @var array $section
     * @var string $currentSection
     * @var string $path
     */
    private static $sectionList = [], $currentSection, $section = [];
    private static ?string $path = null;

    /**
     * Function to define a section
     * @var string $content
     */
    public function section(string $name, string $content = null): void
    {
        if (!is_null($content)) {
            self::$section[$name][] = $content;
        }
        self::$currentSection =  $name;
        self::$sectionList[] = $name;
        ob_start();
    }

    /**
     * Function to end a section
     */
    public function endSection(): void
    {
        $content = ob_get_clean();
        if (self::$sectionList === []) {
            throw new InvalidSectionException();
        }
        $section = array_pop(self::$sectionList);
        if (!array_key_exists($section, self::$section)) {
            self::$section[$section] = [];
        }
        self::$section[$section][] = $content;
        if (self::$path) {
            include_view(self::$path);
        }
    }

    /**
     * Function to get a section
     */
    public function getSection(string $name): void
    {
        if (!isset(self::$section[$name])) {
            echo '';

            return;
        }

        foreach (self::$section[$name] as $key => $contents) {
            echo $contents;
            unset(self::$section[$name][$key]);
        }
    }

    /**
     * Function to extends another views. A section calling is required to make sure everything run just fine.
     */
    public function extends(string $path): void
    {
        if (self::$currentSection) {
            include_view($path);
            return;
        }

        self::$path = $path;
    }

    /**
     * Function to include a view
     */
    public function include(string $path): void
    {
        include_view($path);
    }

    /**
     * Function to matching user's url and server expected url then
     * if match return following classname.
     */
    public function match(string $expected, string $classname): string
    {
        $expected = url() . $expected;
        if ($expected === getCurrentUrl()) {
            return $classname;
        }
        return '';
    }

    private function latteDriver(string $path, array $data): mixed
    {
        $latte = new \Latte\Engine;
        $tmpPath = __DIR__ . '/../../Views/Latte/Temps';
        if (!is_dir($tmpPath)) {
            mkdir(__DIR__ . '/../../Views/Latte');
            mkdir($tmpPath);
        }

        $latte->setTempDirectory($tmpPath);
        if (config('app')['ENV'] == 'production') {
            $latte->setAutoRefresh(false);
        }

        $latte->addFunction('csrf', fn ($route = null): \Latte\Runtime\Html => new \Latte\Runtime\Html(csrf_field($route)));

        $latte->addFunction('getErrorMsg', fn ($field): \Latte\Runtime\Html => new \Latte\Runtime\Html(getErrorMsg($field)));

        $latte->addFunction('flash', fn (): \App\Asmvc\Core\Flash => new Flash);

        $latte->addFunction('match', fn ($url, $htmlclass): string => (new Views)->match($url, $htmlclass));

        $latte->addFunction('url', fn ($url): string => url($url));

        $latte->addFunction('checkError', fn ($field) => checkError($field));

        $latte->addFunction('getErrorMsg', fn ($field) => getErrorMsg($field));

        $view = dotSupport($path);
        return $latte->render(__DIR__ . '/../../Views/' . $view . '.latte', $data);
    }

    /**
     * Import a view
     */
    public function include_view(string $path, array $data): mixed
    {
        if (provider_config()['view'] == 'latte') {
            return $this->latteDriver($path, $data);
        }
        $final = dotSupport($path);
        extract($data);
        include __DIR__ . '/../../Views/' . $final . ".php";
    }
}
