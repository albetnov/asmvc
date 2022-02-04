<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\Config;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testConfigIsArray()
    {
        $cfg = new Config;

        $this->assertIsArray($cfg->defineConnection(), 'Type data of defineConnection() is correct.');
    }

    public function testConfigFailed()
    {
        $cfg = new Config;

        $this->assertIsString($cfg->defineConnection(), 'Type data of defineConeection() is not string.');
    }
}
