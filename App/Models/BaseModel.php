<?php

namespace Albet\Asmvc\Models;

use Albet\Asmvc\Core\CoreModel;

class BaseModel
{
    /**
     * @var CoreModel $db
     */
    protected CoreModel $db;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->db = new CoreModel;
    }
}
