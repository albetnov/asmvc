<?php

namespace Albet\Asmvc\Core;

class SessionManager
{
    private $type;

    public function __construct()
    {
        $this->type = env('SESSION_TYPE', 'redis');
    }
}
