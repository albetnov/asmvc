<?php

namespace Albet\Asmvc\Core;

abstract class BaseMigration
{
    /**
     * @var EloquentDB $schema
     */
    protected $schema;

    /**
     * Constuctor method
     */
    public function __construct()
    {
        $this->schema = (new EloquentDB)->schema();
    }

    /**
     * Up function for running migration.
     */
    abstract function up();

    /**
     * Down function for rollback migration
     */
    abstract function down();
}
