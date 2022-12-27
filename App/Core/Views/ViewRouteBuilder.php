<?php

namespace App\Asmvc\Core\Views;

use App\Asmvc\Core\Exceptions\ArrayIsNotAssiactiveException;

class ViewRouteBuilder
{
    /**
     * Put a view with data
     */
    public function put(string $viewPath, ?array $bind = []): object
    {
        if (count($bind) > 0 && !isAssociativeArray($bind)) {
            throw new ArrayIsNotAssiactiveException();
        }

        $baseViewPath = __DIR__ . "/../../Views/{$viewPath}";
        if (!file_exists($baseViewPath . ".php") && !file_exists($baseViewPath . ".latte")) {
            throw new ViewFileNotFoundException();
        }

        $view = (object) [
            'path' => $viewPath,
            'bind' => $bind
        ];

        return $view;
    }
}
