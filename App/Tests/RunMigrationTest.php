<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\EloquentDB;
use PHPUnit\Framework\TestCase;

class RunMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        EloquentDB::schema()->dropAllTables();
    }

    public function testMigrationFirstTime()
    {
        $this->expectOutputRegex('/\bmigrated\b/i');
        system("php asmvc run:migration");
    }

    public function testMigrationSecondTime()
    {
        $this->expectOutputRegex('/\bexist\b/i');
        system("php asmvc run:migration");
        system("php asmvc run:migration");
    }
}
