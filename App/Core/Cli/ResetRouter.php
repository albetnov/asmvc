<?php

namespace Albet\Asmvc\Core\Cli;

trait ResetRouter
{
    /**
     * Reset Router.php to clear state.
     */
    private function resetRouter()
    {
        $data = <<<'data'
                <?php

                use Albet\Asmvc\Core\Route;
                
                /**
                 * You can use following method for routing:
                 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod, $Middleware).
                 * Route::inline($urlPath, $CallableFunction, $httpMethod, $Middleware).
                 * Route::view($path, [$view, $data], $httpMethod, $Middleware). Note: Array is not necessary if you not using $data.
                 * $httpMethod and $middleWare can be optional.
                 * It can either be
                 * Route::add($urlPath, [Controller::class, 'methodName'], $HttpMethod) for Http method only
                 * or
                 * Route::add($urlPath, [Controller::class, 'methodName'], $Middleware) for Middleware only.
                 * or both of them.
                 * The same rules applies for inline and view. 
                 */
                
                
                //Your route
        
                data;
        file_put_contents(base_path() . "App/Router/url.php", $data);
        echo "File: url.php | Route resetted\n";
    }
}
