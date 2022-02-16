<?php

namespace Albet\Asmvc\Core;

use Albet\Asmvc\Models\Test;
use Faker\Factory;

abstract class Seeders
{
    /**
     * @var Faker\Factory $faker
     * @var string $lang
     */
    protected $faker, $lang;

    /**
     * Initiate faker based on lang.
     */
    public function __construct()
    {
        if ($this->lang) {
            $this->faker = Factory::create($this->lang);
        } else {
            $this->faker = Factory::create();
        }
    }

    /**
     * Loop your database insert statement.
     * @param int $count
     * @param string|callable $table
     * @param array $data
     */
    public function seed($count, $table, $data)
    {
        for ($i = 0; $i < $count; $i++) {
            if (class_exists($table)) {
                $table::insert($data);
            } else {
                EloquentDB::table($table)->insert($data);
            }
        }
    }

    /**
     * Determine user to override run function.
     */
    abstract function run();
}
