<?php

namespace Albet\Asmvc\Core;

use Illuminate\Database\Schema\Builder;

abstract class BaseMigration
{
    protected Builder $schema;

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
    abstract function up(): void;

    /**
     * Down function for rollback migration
     */
    abstract function down(): void;
}
