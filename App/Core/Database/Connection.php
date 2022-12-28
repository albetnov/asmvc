<?php

namespace App\Asmvc\Core\Database;

class Connection
{
    /**
     * Function to get a connection
     */
    public function getConnection(): \PDO
    {
        $conn = provider_config()['database'];
        return new \PDO("mysql:dbname={$conn['db_name']};dbhost={$conn['db_host']}", $conn['db_user'], $conn['db_pass']);
    }
}
