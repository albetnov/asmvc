<?php

namespace Albet\Ppob\Core;

class CoreModel
{
    /**
     * Mendifinisikan tabel, field, dan juga value
     */
    protected $table, $field = [], $value = [], $whereStmt = null; 
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new Connection)->getConnection();
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
     * Memasukkan field ke dalam variabel.
     */
    public function field(...$field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Memasukkan value ke dalam variabel.
     */
    public function value(...$value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Fungsi where.
     */
    public function where($field, $value, $operator = null)
    {
        $string = "WHERE {$field} ";
        if(!is_null($operator)) {
            $string .= "{$operator} ";
        } else {
            $string .= "= ";
        }
        $string .= "{$value}";
        $this->whereStmt = $string;
        return $this;
    }

    /**
     * Digunakan untuk melakukan proses pada field.
     */
    private function processField(): string
    {
        return implode(',', $this->field);
    }
    
    /**
     * Digunakan untuk memproses banyak prepare statement yang harus
     * disiapkan.
     */
    private function processTotalPrepare(): string {
        $total_prepare = "";
        for($i = 0; $i < count($this->value); $i++) {
            $total_prepare .= "?,";
        }
        return rtrim($total_prepare, ',');
    }

    /**
     * Melakukan validasi apakah ada where atau tidak.
     */
    private function validateWhere() {
        if(!is_null($this->whereStmt)) {
            return $this->whereStmt;
        } else {
            return "";
        }
    }

     /**
     * Method insert untuk memasukkan data ke database sesuai dengan method:
     * table(), field(), dan value().
     */
    public function insert()
    {
        $prepare = $this->pdo->prepare("INSERT INTO {$this->table} ({$this->processField()}) VALUES ({$this->processTotalPrepare()})");
        $attempt = $prepare->execute($this->value);
        if($attempt) {
            return true;
        }
    }

    /**
     * Fungsi update
     */
    public function update()
    {
        $prepare = "SET ";
        for($i = 0; $i < count($this->value); $i++) {
            $prepare .= "{$this->field[$i]} = ?,";
        }
        $prepare = rtrim($prepare, ',')." {$this->validateWhere()}";
        $prepare = $this->pdo->prepare("UPDATE {$this->table} {$prepare}");
        $attempt = $prepare->execute($this->value);
        if($attempt) {
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
        if($attempt) {
            return true;
        }
    }
}
