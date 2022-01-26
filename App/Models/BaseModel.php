<?php

namespace Albet\Asmvc\Models;

use Albet\Asmvc\Core\CoreModel;

class BaseModel
{
    protected CoreModel $db;

    public function __construct()
    {
        $this->db = new CoreModel;
    }
}
