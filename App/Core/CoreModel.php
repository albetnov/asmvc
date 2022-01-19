<?php

namespace Albet\Ppob\Core;

class CoreModel
{
    /**
     * Mendifinisikan tabel, field, dan juga value
     */
    protected $table, $data, $whereStmt = null;
    private $pdo;

    /**
     * Iniliasisasi Koneksi
     */
    public function __construct()
    {
        $this->pdo = (new Connection)->getConnection();
    }

    /**
     * Melakukan proses pada field
     */
    private function processField()
    {
        return implode(',', array_keys($this->data));
    }

    /**
     * Menghitung jumlah prepare.
     */
    private function totalPrepare()
    {
        $total_prepare = "";
        for ($i = 0; $i < count($this->data); $i++) {
            $total_prepare .= "?,";
        }
        return rtrim($total_prepare, ',');
    }

    /**
     * Memasukkan tabel ke dalam variabel
     */
    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    /**
     * Fungsi where.
     */
    public function where($field, $value, $operator = null)
    {
        $string = "WHERE {$field} ";
        if (!is_null($operator)) {
            $string .= "{$operator} ";
        } else {
            $string .= "= ";
        }
        $string .= "{$value}";
        $this->whereStmt = $string;
        return $this;
    }

    /**
     * Melakukan validasi apakah ada where atau tidak.
     */
    private function validateWhere()
    {
        if (!is_null($this->whereStmt)) {
            return $this->whereStmt;
        } else {
            return "";
        }
    }

    /**
     * Fungsi insert
     */
    public function insert(array $data)
    {
        $this->data = $data;
        $prepare = $this->pdo->prepare("INSERT INTO {$this->table} ({$this->processField()}) VALUES ({$this->totalPrepare()})");
        $attempt = $prepare->execute(array_values($data));
        if ($attempt) {
            return true;
        }
    }

    /**
     * Fungsi update
     */
    public function update(array $data)
    {
        $this->data = $data;
        $prepare = "SET ";
        $field = array_keys($data);
        for ($i = 0; $i < count($data); $i++) {
            $prepare .= "{$field[$i]} = ?,";
        }
        $prepare = rtrim($prepare, ',') . " {$this->validateWhere()}";
        $prepare = $this->pdo->prepare("UPDATE {$this->table} {$prepare}");
        $attempt = $prepare->execute(array_values($data));
        if ($attempt) {
            return true;
        }
    }

    /**
     * Fungsi delete
     */
    public function delete()
    {
        $prepare = $this->pdo->prepare("DELETE FROM {$this->table} {$this->validateWhere()}");
        $attempt = $prepare->execute();
        if ($attempt) {
            return true;
        }
    }
}
