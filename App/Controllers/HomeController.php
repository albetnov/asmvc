<?php

namespace Albet\Asmvc\Controllers;

use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Validator;
use Albet\Asmvc\Models\TestModel;

class HomeController
{
    public function index()
    {
        return view('home');
    }
}
