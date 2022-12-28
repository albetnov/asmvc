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
        if ((array) $bind !== [] && !isAssociativeArray($bind)) {
            throw new ArrayIsNotAssiactiveException();
        }

        $baseViewPath = __DIR__ . "/../../Views/{$viewPath}";
        if (file_exists($baseViewPath . ".php")) {
            return (object) [
                'path' => $viewPath,
                'bind' => $bind
            ];
        }

        if (file_exists($baseViewPath . ".latte")) {
            return (object) [
                'path' => $viewPath,
                'bind' => $bind
            ];
        }

        throw new ViewFileNotFoundException();
    }
}
