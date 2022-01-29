<?php

namespace Albet\Asmvc\Controllers;

class BaseController
{
    /**
     * View Functions
     * @param string $name, array $data
     */
    public function view($name, array $data = [])
    {
        extract($data);
        $final = dotSupport($name);
        require_once __DIR__ . '/../Views/' . $final . '.php';
    }

    /**
     * Define main controller or entry point controller name here.
     * @return string
     */
    public function mainController(): string
    {
        return 'HomeController';
    }

    /**
     * Define default method of entry controller here.
     * Does not apply to all controllers. Limited to main or entry controller only
     * @return string
     */
    public function defaultMethod(): string
    {
        return "index";
    }

    /**
     * Define default middleware used for entry controller here.
     * Does not apply to all controllers. Limited to main or entry controller only
     * @return string
     */
    public function defaultMiddleware(): string
    {
        return '';
    }
}
