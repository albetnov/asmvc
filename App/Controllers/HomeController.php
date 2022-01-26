<?php

namespace Albet\Asmvc\Controllers;

use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Validator;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home');
    }
}
