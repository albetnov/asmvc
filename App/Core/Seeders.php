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
     * @var int $count
     */
    private $count;

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
     * Count how much the seeder will be executed.
     * @param int $count
     * @return self
     */
    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Loop your database insert statement.
     * @param string|callable $table
     * @param array $data
     */
    public function seed($table, $data)
    {
        for ($i = 0; $i < $this->count; $i++) {
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
