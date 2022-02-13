<?php

namespace Albet\Asmvc\Core\Cli;

trait addBootstrap
{
    /**
     * Add bootstrap to index.php.
     * @param string $options
     */
    private function addBootstrap()
    {
        $data = <<<'data'
        <?php

        require_once __DIR__ . '/../App/Core/init.php';
        
        use Albet\Asmvc\Core\Route;
        use Albet\Asmvc\Core\SessionManager;
        
        SessionManager::runSession();
        csrf()->generateCsrf();
        define('BS5_CSS', 'css/bootstrap.min.css');
        define('BS5_JS', 'js/bootstrap.min.js');
        
        /**
         * Calling your route
         */
        require_once __DIR__ . '/../App/Router/url.php';
        Route::triggerRouter();
        
        data;
        file_put_contents(base_path() . 'public/index.php', $data);
    }
}
