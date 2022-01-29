<?php

namespace Albet\Asmvc\Core;

class Views
{
    /**
     * @var array $sectionList, $section,string $currentSection
     */
    private static $sectionList = [], $currentSection, $section = [];

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
            throw new \Exception("Section anda kosong.");
        }
        $section = array_pop(self::$sectionList);
        if (!array_key_exists($section, self::$section)) {
            self::$section[$section] = [];
        }
        self::$section[$section][] = $content;
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
     * Function to extends or include another views.
     * @param string $path
     */
    public function extends($path)
    {
        return v_include($path);
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
}
