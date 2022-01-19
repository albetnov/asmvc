<?php

namespace Albet\Ppob\Models;

use Albet\Ppob\Core\CoreModel;

class BaseModel
{
    protected CoreModel $db;

    public function __construct()
    {
        $this->db = new CoreModel;
    }
}
