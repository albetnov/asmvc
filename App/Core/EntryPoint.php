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
     * @param $inline, $middleware
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
}
