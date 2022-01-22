<?php

namespace Albet\Ppob\Core;

class Views
{
    private static $sectionList = [], $currentSection, $section = [];

    public function section($name, $content = null)
    {
        if (!is_null($content)) {
            self::$section[$name][] = $content;
        }
        self::$currentSection =  $name;
        self::$sectionList[] = $name;
        ob_start();
    }

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

    public function extends($path)
    {
        return v_include($path);
    }
}
