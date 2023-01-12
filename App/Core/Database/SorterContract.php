<?php

namespace App\Asmvc\Core\Database;

interface SorterContract
{
    public function migrations(): ?array;
    public function seeders(): ?array;
    public function exceptMigration(): ?array;
    public function exceptSeeder(): ?array;
}
