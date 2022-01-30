<?php

namespace Albet\Asmvc\Core\Cli;

trait ResetIndex
{
    /**
     * Reset Index.php to Boostrap Ready/Not state.
     * @param string $options
     */
    private function resetIndex($options = null)
    {
        if ($options == 'add_bs') {
            $data = <<<'data'
            <?php

            require_once __DIR__ . '/../App/Core/init.php';

            use Albet\Asmvc\Core\Route;

            csrf()->generateCsrf();
            define('BS5_CSS', 'css/bootstrap.min.css');
            define('BS5_JS', 'js/bootstrap.min.js');

            /**
             * Calling your route
             */
            require_once __DIR__ . '/../App/Router/url.php';
            Route::triggerRouter();
    
            data;
        } else {
            $data = <<<'data'
            <?php

            require_once __DIR__ . '/../App/Core/init.php';

            use Albet\Asmvc\Core\Route;

            csrf()->generateCsrf();
            define('BS5_CSS', '');
            define('BS5_JS', '');

            /**
             * Calling your route
             */
            require_once __DIR__ . '/../App/Router/url.php';
            Route::triggerRouter();

            data;
        }

        file_put_contents(base_path() . 'public/index.php', $data);
    }
}
