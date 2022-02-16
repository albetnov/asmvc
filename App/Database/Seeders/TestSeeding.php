<?php

namespace Albet\Asmvc\Database\Seeders;

use Albet\Asmvc\Core\Seeders;
use Albet\Asmvc\Models\Test;

class TestSeeding extends Seeders
{
    public function run()
    {
        $this->seed(1, Test::class, [
            [
                'test' => 'test multiple model1'
            ],
            [
                'test' => 'test multiple model2'
            ]
        ]);
    }
}
