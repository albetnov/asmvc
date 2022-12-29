<?php

namespace App\Asmvc\Controllers;

use App\Asmvc\Core\Requests;
use App\Asmvc\Core\REST\Rest;

class HomeController
{
    public function index()
    {
        return include_view('home');
    }
}
