<?php

namespace Albet\Ppob\Core;

class BaseMiddleware
{
    protected CoreModel $db;

    public function __construct()
    {
        $this->db = new CoreModel;
    }

    /**
     * Ganti isi dari method ini dengan overriding!
     */
    public function denied()
    {
        return redirect('/login');
    }
}
