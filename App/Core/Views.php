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
            return view(self::$path);
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
            return view($path);
        }

        self::$path = $path;
    }

    /**
     * Function to include a view
     * @param string $path
     */
    public function include(string $path): void
    {
        return view($path);
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
        if ($expected == GetCurrentUrl()) {
            return $classname;
        } else {
            return '';
        }
    }

    private function latteDriver(string $path, array $data): void
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

        $latte->addFunction('validateMsg', function ($field) {
            return new \Latte\Runtime\Html(validateMsg($field));
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
    public function view(string $path, array $data): void
    {
        if (Config::viewEngine() == 'latte') {
            return $this->latteDriver($path, $data);
        }
        $final = dotSupport($path);
        extract($data);
        include __DIR__ . '/../Views/' . $final . ".php";
    }
}
