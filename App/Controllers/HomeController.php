<?php

namespace Albet\Ppob\Controllers;

use Albet\Ppob\Core\Requests;
use Albet\Ppob\Core\Validator;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home');
    }
}
