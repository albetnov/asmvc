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
     * @param $path, $middleware
     * @return array
     */
    protected function view($path, $middleware = null)
    {
        $array['path'] = $path;
        if (!is_null($middleware)) {
            $array['middleware'] = $middleware;
        }
        return $array;
    }
}
