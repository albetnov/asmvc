<?php

namespace Albet\Asmvc\Core;

class Connection
{

    /**
     * Define your database connection 
     * @return array
     */
    public function defineConnection(): array
    {
        /**
         * You're free to configure this array.
         */
        return [
            'db_host' => 'localhost',
            'db_name' => 'asmvc',
            'db_user' => 'root',
            'db_pass' => ''
        ];
    }

    /**
     * Function to get a connection
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        $conn = $this->defineConnection();
        return new \PDO("mysql:dbname={$conn['db_name']};dbhost={$conn['db_host']}", $conn['db_user'], $conn['db_pass']);
    }
}
