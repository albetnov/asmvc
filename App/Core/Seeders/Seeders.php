<?php

namespace App\Asmvc\Core\Seeders;

use App\Asmvc\Core\Eloquent\EloquentDB;
use Closure;
use Faker\Factory;

abstract class Seeders
{
    private ?string $lang = null;
    private ?string $table = null;
    private array $payload = [];

    /**
     * Set language.
     *  make sure this method called before calling fake() method. If you want to use it.
     */
    protected function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Generate fake data
     */
    protected function fake(int $count, Closure $faker): self
    {
        $seeder = $this->lang ? $faker((new Factory())->create($this->lang)) : $faker((new Factory())->create());

        $payload = [];

        for ($i = 0; $i < $count; $i++) {
            $payload[] = $seeder;
        }

        $this->payload = $payload;
        return $this;
    }

    /**
     * Set table
     */
    protected function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Append the payload
     */
    protected function addPayload(array $payload): self
    {
        if (!isAssociativeArray($payload)) {
            throw new PayloadInvalidException();
        }
        $this->payload[] = $payload;
        return $this;
    }

    /**
     * Override the payload
     */
    protected function setPayload(array $payload = []): self
    {
        if (!isAssociativeArray($payload)) {
            throw new PayloadInvalidException();
        }
        $this->payload = $payload;
        return $this;
    }

    /**
     * Mark as done
     */
    public function done(): void
    {
        if (count($this->payload) <= 0) {
            throw new PayloadInvalidException();
        }

        if (!$this->table) {
            throw new TableInvalidException();
        }

        if (class_exists($this->table)) {
            $this->table::insert($this->payload);
        } else {
            EloquentDB::table($this->table)->insert($this->payload);
        }
    }

    /**
     * Determine user to override run function.
     */
    abstract function run(): void;
}
