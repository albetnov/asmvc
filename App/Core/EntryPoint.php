<?php

namespace Albet\Asmvc\Core;

class EntryPoint
{
    /**
     * Controller entry point function
     * @param $name, $method, $middleware
     * @return array
     */
    protected function controller($name, $method, $middleware = null)
    {
        $array = [
            'controller' => $name,
            'method' => $method
        ];
        if (!is_null($middleware)) {
            $array['middleware'] = $middleware;
        }
        return $array;
    }

    /**
     * View entry point function
     * @param $view, $middleware
     * @return array
     */
    protected function view($view, $middleware = null)
    {
        if (is_array($view)) {
            $array['path'] = $view[0];
            $array['data'] = $view[1];
        } else {
            $array['path'] = $view;
        }
        if (!is_null($middleware)) {
            $array['middleware'] = $middleware;
        }
        return $array;
    }

    /**
     * Inline function entry point
     * @param $inline, 
     * @param $middleware
     * @return array
     */
    protected function inline($inline, $middleware = null)
    {
        $array['inline'] = $inline;
        if (!is_null($middleware)) {
            $array['middleware'] = $middleware;
        }
        return $array;
    }

    /**
     * Decide which method to use for entry point based on ENV
     * @return array
     */
    protected function fromEnv()
    {
        $entry_type = env('ENTRY_TYPE', 'controller');
        $entry_class = env('ENTRY_CLASS', 'HomeController');
        $entry_method = env("ENTRY_METHOD", 'index');
        $entry_middleware = env('ENTRY_MIDDLEWARE');
        if ($entry_type == 'controller') {
            return $this->controller("\\Albet\\Asmvc\\Controllers\\" . $entry_class, $entry_method, $entry_middleware);
        } else if ($entry_type == 'view') {
            if ($entry_method == '') {
                return $this->view($entry_class, $entry_middleware);
            }
            $parsed = [];
            $parse = explode(',', $entry_method);
            foreach ($parse as $parse) {
                $parsing = explode('.', $parse);
                foreach ($parsing as $parsing) {
                    $parsed[getStringBefore('.', $parse)] = $parsing;
                }
            }
            return $this->view([$entry_class, $parsed], $entry_middleware);
        } else {
            throw new \Exception("Inline not supported to be adjusted in .env. Please adjust in manually in Core/Config.php");
        }
    }
}
