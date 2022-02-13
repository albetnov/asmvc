<?php

namespace Albet\Asmvc\Core;

class Views
{
    /**
     * @var array $sectionList, $section,string $currentSection
     */
    private static $sectionList = [], $currentSection, $section = [];
    private static $path;

    /**
     * Function to define a section
     * @param string $name, $content
     */
    public function section($name, $content = null)
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
    public function endSection()
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
    public function getSection($name)
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
    public function extends($path)
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
    public function include($path)
    {
        return view($path);
    }

    /**
     * Function to matching user's url and server expected url then
     * if match return following classname.
     * @param string $expected, $classname
     * @return string
     */
    public function match($expected, $classname)
    {
        $expected = url() . $expected;
        if ($expected == GetCurrentUrl()) {
            return $classname;
        } else {
            return '';
        }
    }

    public function view($path, $data)
    {
        $final = dotSupport($path);
        extract($data);
        include __DIR__ . '/../Views/' . $final . ".php";
    }
}
