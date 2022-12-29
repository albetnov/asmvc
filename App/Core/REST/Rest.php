<?php

namespace App\Asmvc\Core\REST;

class Rest
{
    public function json(array $data, ?int $statusCode = 200): never
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    public function setAcceptJson(): never
    {
        header('Accept: application/json');
        exit;
    }
}
