<?php

namespace Albet\Ppob\Core;

class Connection
{

    /**
     * Definisikan koneksi anda disini.
     */
    public function defineConnection(): array
    {
        /**
         * Sihlakan ubah sesuai konfigurasi database anda.
         */
        return [
            'db_host' => 'localhost',
            'db_name' => 'laundry',
            'db_user' => 'root',
            'db_pass' => ''
        ];
    }

    /**
     * Function untuk mendapatkan koneksi
     */
    public function getConnection(): \PDO
    {
        $conn = $this->defineConnection();
        return new \PDO("mysql:dbname={$conn['db_name']};dbhost={$conn['db_host']}", $conn['db_user'], $conn['db_pass']);
    }
}
