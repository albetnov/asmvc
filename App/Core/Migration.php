<?php

namespace App\Asmvc\Core;

use App\Asmvc\Core\Eloquent\EloquentDB;
use Illuminate\Database\Schema\Builder;

abstract class Migration
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
