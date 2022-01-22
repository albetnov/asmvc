<?php

namespace Albet\Ppob\Controllers;

use Albet\Ppob\Core\Requests;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home');
    }
}
