<?php

namespace Albet\Asmvc\Tests;

use Albet\Asmvc\Core\EloquentDB;
use Albet\Asmvc\Models\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use PDOException;
use PHPUnit\Framework\TestCase;

class EloquentTest extends TestCase
{

    protected function setUp(): void
    {
        EloquentDB::schema()->dropIfExists('test-table');
        EloquentDB::schema()->dropIfExists('test-table-duplicate');
    }

    public function testGettingUsers()
    {
        $fetch = Users::get();
        $this->assertIsObject($fetch, "Fetching failed.");
    }

    public function testFailedGettingUsers()
    {
        $this->expectException(QueryException::class);
        NoTable::get();
    }

    public function testQueryBuilder()
    {
        $fetch = EloquentDB::table('users')->get();
        $this->assertIsObject($fetch, "Query builder fetch failed.");
    }

    public function testQueryBuilderFails()
    {
        $this->expectException(QueryException::class);
        EloquentDB::table('no-table')->get();
    }

    public function testSchemaMigration()
    {
        $create = EloquentDB::schema()->create('test-table', function ($table) {
            $table->increments('id');
            $table->string('something');
            $table->integer('num');
        });
        $this->assertNull($create, 'Failed creating table');
    }

    public function testDuplicateMigration()
    {
        $this->expectException(PDOException::class);
        EloquentDB::schema()->create('test-table-duplicate', function ($table) {
            $table->increments('id');
            $table->string('something');
            $table->integer('num');
        });
        EloquentDB::schema()->create('test-table-duplicate', function ($table) {
            $table->increments('id');
            $table->string('something');
            $table->integer('num');
        });
    }
}

class NoTable extends Model
{
    protected $table = "No-Table";
}
