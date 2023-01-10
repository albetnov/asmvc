<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Exceptions\DetailableException;

class InvalidConfigException extends DetailableException
{
    public function __construct(string $module, ?array $only = [])
    {
        $message = "Key: $module is invalid.";
        if (count($only) > 0) {
            $message = "Key: $module, Supported values only: " . implode(", ", $only);
        }
        parent::__construct($message);
    }

    public function getDetail(): string
    {
        return "Please make sure Config/cors.php has all the required field and ensure they have the correct value.";
    }
}
