<?php

namespace Albet\Asmvc\Core;

class Views
{
    /**
     * @var array $sectionList 
     * @var array $section
     * @var string $currentSection
     * @var string $path
     */
    private static $sectionList = [], $currentSection, $section = [];
    private static $path;

    /**
     * Function to define a section
     * @param string $name
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
            throw new \Exception("Section undefined.");
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
     * @param string $name
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
     * @param string $path
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
     * @param string $path
     */
    public function include(string $path): void
    {
        include_view($path);
    }

    /**
     * Function to matching user's url and server expected url then
     * if match return following classname.
     * @param string $expected
     * @param string $classname
     * @return string
     */
    public function match(string $expected, string $classname): string
    {
        $expected = url() . $expected;
        if ($expected == getCurrentUrl()) {
            return $classname;
        } else {
            return '';
        }
    }

    private function latteDriver(string $path, array $data): mixed
    {
        $latte = new \Latte\Engine;
        $tmpPath = __DIR__ . '/../Views/Latte/Temps';
        if (!is_dir($tmpPath)) {
            mkdir(__DIR__ . '/../Views/Latte');
            mkdir($tmpPath);
        }

        $latte->setTempDirectory($tmpPath);
        if (env('APP_ENV') == 'production') {
            $latte->setAutoRefresh(false);
        }

        $latte->addFunction('csrf', function ($route = null) {
            return new \Latte\Runtime\Html(csrf_field($route));
        });

        $latte->addFunction('getErrorMsg', function ($field) {
            return new \Latte\Runtime\Html(getErrorMsg($field));
        });

        $latte->addFunction('flash', function () {
            return new Flash;
        });

        $latte->addFunction('match', function ($url, $htmlclass) {
            return (new Views)->match($url, $htmlclass);
        });

        $latte->addFunction('url', function ($url) {
            return url($url);
        });

        $view = dotSupport($path);
        return $latte->render(__DIR__ . '/../Views/' . $view . '.latte', $data);
    }

    /**
     * Import a view
     */
    public function include_view(string $path, array $data): mixed
    {
        if (Config::viewEngine() == 'latte') {
            return $this->latteDriver($path, $data);
        }
        $final = dotSupport($path);
        extract($data);
        include __DIR__ . '/../Views/' . $final . ".php";
    }
}
