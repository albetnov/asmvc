<?php

namespace Albet\Asmvc\Database\Migrations;

use Albet\Asmvc\Core\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

return new class extends BaseMigration
{
    public function up()
    {
        $this->schema->create('example-migration', function (Blueprint $table) {
            $table->id();
            $table->string('test-column');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('example-table');
    }
};
