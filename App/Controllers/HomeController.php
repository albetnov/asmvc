<?php

namespace Albet\Asmvc\Controllers;

use Albet\Asmvc\Core\Requests;
use Albet\Asmvc\Core\Validator;
use Albet\Asmvc\Models\TestModel;

class HomeController
{

    public function __construct()
    {
        $this->testmodel = new TestModel;
    }

    public function index()
    {
        return view('home');
    }

    public function testModel()
    {
        print_r($this->testmodel->get());
    }
}
