<?php

namespace Albet\Ppob\Controllers;

use Albet\Ppob\Models\TestModel;

class HomeController extends BaseController
{

    private $testmodel;

    public function __construct()
    {
        $this->testmodel = new TestModel;
    }

    public function index()
    {
        $this->view('home', [
            'title' => 'Home',
        ]);
    }

    public function testModel()
    {
        echo $this->testmodel->test();
    }
}
