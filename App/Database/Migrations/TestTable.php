<?php

namespace Albet\Asmvc\Database\Migrations;

use Albet\Asmvc\Core\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

return new class extends BaseMigration
{
    public function up()
    {
        $this->schema->create('test-class', function (Blueprint $table) {
            $table->id();
            $table->string('test');
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('test-class');
    }
};
