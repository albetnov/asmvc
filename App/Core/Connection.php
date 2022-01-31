<?php

namespace Albet\Asmvc\Core;

class Connection
{
    /**
     * Function to get a connection
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        $conn = (new Config)->defineConnection();
        return new \PDO("mysql:dbname={$conn['db_name']};dbhost={$conn['db_host']}", $conn['db_user'], $conn['db_pass']);
    }
}
