<?php

namespace Albet\Ppob\Middleware;

use Albet\Ppob\Core\BaseMiddleware;

class LoggedIn extends BaseMiddleware
{
    public function middleware()
    {
        if (!isset($_SESSION[['logged_in']])) {
            $this->denied();
        }
    }
}
