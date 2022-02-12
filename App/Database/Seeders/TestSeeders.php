<?php

namespace Albet\Asmvc\Database\Seeders;

use Albet\Asmvc\Core\EloquentDB;
use Albet\Asmvc\Core\Seeders;

class TestSeeders extends Seeders
{
    public function run()
    {
        $this->seed(10, function () {
            EloquentDB::table('test-lagi')->insert([
                'test' => $this->faker->name()
            ]);
        });
    }
}
