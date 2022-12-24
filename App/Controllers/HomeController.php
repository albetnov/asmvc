<?php

namespace Albet\Asmvc\Controllers;

use Albet\Asmvc\Core\Requests;

class HomeController
{
    public function index(): void
    {
        return view('home');
    }
}
