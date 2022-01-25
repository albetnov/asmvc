<?php

namespace Albet\Ppob\Tests;

require_once __DIR__ . '/../Core/init.php';

use Albet\Ppob\Controllers\BaseController;
use Albet\Ppob\Controllers\HomeController;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testBaseController()
    {
        $base = new BaseController;

        $this->assertIsString($base->mainController(), 'Ini bukan string!');
    }

    public function testBaseIsCorrect()
    {
        $base = new BaseController;
        $mainController = "Albet\\Ppob\\Controllers\\{$base->mainController()}";
        $call_main = new $mainController();
        $method = $base->defaultMethod();
        define('BS5_CSS', 'css/bootstrap.min.css');
        define('BS5_JS', 'js/bootstrap.min.js');
        $this->assertInstanceOf($mainController, $call_main, 'Class yang kamu gunakan salah!');
    }
}
