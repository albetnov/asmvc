<?php

namespace Albet\Asmvc\Core;

use Illuminate\Support\Facades\Schema;

abstract class BaseMigration
{
    protected $schema;
    public function __construct()
    {
        $this->schema = (new EloquentDB)->schema();
    }

    abstract function up();

    abstract function down();
}
