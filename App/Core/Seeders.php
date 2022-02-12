<?php

namespace Albet\Asmvc\Core;

use Faker\Factory;

abstract class Seeders
{
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
     */
    public function seed($count, $callback)
    {
        for ($i = 0; $i < $count; $i++) {
            call_user_func($callback);
        }
    }

    /**
     * Determine user to override run function.
     */
    abstract function run();
}
