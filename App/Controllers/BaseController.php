<?php

namespace Albet\Asmvc\Controllers;

class BaseController
{
    /**
     * Function view
     */
    public function view($name, array $data = [])
    {
        extract($data);
        $final = dotSupport($name);
        require_once __DIR__ . '/../Views/' . $final . '.php';
    }

    /**
     * Disini kita bisa mengganti nama controller.
     */
    public function mainController(): string
    {
        return 'HomeController';
    }

    /**
     * Disini kita bisa mengganti method default yang dipanggil.
     */
    public function defaultMethod(): string
    {
        return "index";
    }

    public function defaultMiddleware(): string
    {
        return '';
    }
}
