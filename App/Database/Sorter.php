<?php

namespace App\Asmvc\Database;

use App\Asmvc\Core\Database\SorterContract;

class Sorter implements SorterContract
{
    public function migrations(): ?array
    {
        return null;
    }

    public function seeders(): ?array
    {
        return null;
    }

    public function exceptMigration(): ?array
    {
        return null;
    }

    public function exceptSeeder(): ?array
    {
        return null;
    }
}
