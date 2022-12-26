<?php

namespace Albet\Asmvc\Core\Database;

class Connection
{
    /**
     * Function to get a connection
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        $conn = provider_config()['database'];
        return new \PDO("mysql:dbname={$conn['db_name']};dbhost={$conn['db_host']}", $conn['db_user'], $conn['db_pass']);
    }
}