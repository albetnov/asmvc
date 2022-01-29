<?php

namespace Albet\Asmvc\Core;

class CoreModel
{
    /**
     * Define require variables
     */
    private $table, $data;
    private $whereStmt = null, $orderStmt, $limitStmt, $joinStmt, $whereNoFormat = false;
    private $pdo;
    private static $last_insert_id;

    /**
     * Initiating Connection
     */
    public function __construct()
    {
        $this->pdo = (new Connection)->getConnection();
    }

    /**
     * Format the field to be used.
     */
    private function processField()
    {
        return implode(',', array_keys($this->data));
    }

    /**
     * Count required prepare statement
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
     * Define Table Function
     * @param string $name
     * @return self
     */
    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    /**
     * Where function
     * @param string $field, $value, $operator
     * @return self
     */
    public function where($field, $value, $operator = null)
    {
        if ($this->whereStmt) {
            $string = " AND {$field} ";
        } else {
            $string = "WHERE {$field} ";
        }
        if (!is_null($operator)) {
            $string .= "{$operator} ";
        } else {
            $string .= "= ";
        }
        if ($this->whereNoFormat) {
            $string .= "{$value}";
        } else {
            $string .= "'{$value}'";
        }
        $this->whereStmt .= $string;
        return $this;
    }

    /**
     * Where function without formatted strings.
     * @return self
     */
    public function whereNoFormat()
    {
        if ($this->whereStmt) {
            throw new \Exception("Please use whereNoFormat before where()");
        }
        $this->whereNoFormat = true;
        return $this;
    }

    /**
     * orWhere function
     * @param string $field, $value, $operator
     * @return self
     */
    public function orWhere($field, $value, $operator = null)
    {
        if (!$this->whereStmt) {
            throw new \Exception("Please use orWhere() after where()");
        }
        if ($this->whereStmt) {
            $string = " OR {$field} ";
        }
        if (!is_null($operator)) {
            $string .= "{$operator} ";
        } else {
            $string .= "= ";
        }
        $string .= "'{$value}'";
        $this->whereStmt .= $string;
        return $this;
    }

    /**
     * Order by function
     * @param string $column, $order
     * @return self
     */
    public function orderBy($column, $order)
    {
        noSelfChained($this->orderStmt, 'orderBy');
        if (!is_array($column)) {
            $string = "ORDER BY {$column} {$order}";
        } else if (is_array($column) && count($column) > 1) {
            $join = implode(',', $column);
            $string = "ORDER BY {$join} {$order}";
        }
        $this->orderStmt = $string;
        return $this;
    }

    /**
     * Limit function
     * @param int $limit
     * @return self
     */
    public function limit(int $limit)
    {
        noSelfChained($this->limitStmt, 'limit');
        $string = "LIMIT {$limit}";
        $this->limitStmt = $string;
        return $this;
    }

    /**
     * Join Function
     * @param string $table, $from_id, $to_id
     * @return self
     */
    public function join($table, $from_id, $to_id)
    {
        $string = "";
        if ($this->joinStmt) {
            $string = " ";
        }
        $string .= "INNER JOIN {$table} ON {$from_id} = {$to_id}";
        $this->joinStmt .= $string;
        return $this;
    }

    /**
     * Run and format every possible query statement in use
     * @return string
     */
    private function validateOptional()
    {
        $string = "";
        if (!is_null($this->orderStmt)) {
            $string .= $this->orderStmt . " ";
        }
        if (!is_null($this->limitStmt)) {
            $string .= $this->limitStmt . " ";
        }
        if (!is_null($this->joinStmt)) {
            $string .= $this->joinStmt . " ";
        }
        if (!is_null($this->whereStmt)) {
            $string .= $this->whereStmt . " ";
        }
        return trim($string);
    }

    /**
     * Clean function to clean the entire variables.
     */
    private function clean()
    {
        $this->table = '';
        $this->data = '';
        $this->whereStmt = null;
        $this->orderStmt = null;
        $this->limitStmt = null;
        $this->joinStmt = null;
    }

    /**
     * Fetching data
     * @param array $fields
     */
    public function get($fields = [])
    {
        $sql = $this->pdo->query("SELECT * FROM {$this->table} {$this->validateOptional()}");
        if ($fields != []) {
            $fields = implode(',', $fields);
            $sql =  $this->pdo->query("SELECT {$fields} FROM {$this->table} {$this->validateOptional()}");
        }
        $sql->execute();
        $this->clean();
        return $sql->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Get the first data from fetch
     * @param array $fields
     */
    public function first($fields = [])
    {
        $sql = $this->pdo->query("SELECT * FROM {$this->table} {$this->validateOptional()} LIMIT 1");
        if ($fields != []) {
            $fields = implode(',', $fields);
            $sql = $this->pdo->query("SELECT {$fields} FROM {$this->table} {$this->validateOptional()} LIMIT 1");
        }
        $sql->execute();
        foreach ($sql->fetchAll(\PDO::FETCH_OBJ) as $result) {
        }
        $this->clean();
        return $result;
    }

    /**
     * Count a table
     */
    public function count()
    {
        $sql = $this->pdo->query("SELECT COUNT(*) AS result FROM {$this->table} {$this->validateOptional()}");
        $sql->execute();
        foreach ($sql->fetchAll(\PDO::FETCH_OBJ) as $result) {
        }
        $this->clean();
        return $result;
    }

    /**
     * Insert a data
     * @param array $data, boolean $last_insert_id
     */
    public function insert(array $data, $last_insert_id = false)
    {
        $this->data = $data;
        $prepare = $this->pdo->prepare("INSERT INTO {$this->table} ({$this->processField()}) VALUES ({$this->totalPrepare()})");
        $attempt = $prepare->execute(array_values($data));
        if ($last_insert_id) {
            self::$last_insert_id =  $this->pdo->lastInsertId();
        }
        $this->clean();
        if ($attempt) {
            return true;
        }
    }

    /**
     * Get Last Insert Id
     */
    public function lastInsertId()
    {
        return self::$last_insert_id;
    }

    /**
     * Update function
     * @param array $data
     */
    public function update(array $data)
    {
        $this->data = $data;
        $prepare = "SET ";
        $field = array_keys($data);
        for ($i = 0; $i < count($data); $i++) {
            $prepare .= "{$field[$i]} = ?,";
        }
        $prepare = rtrim($prepare, ',') . " {$this->validateOptional()}";
        $prepare = $this->pdo->prepare("UPDATE {$this->table} {$prepare}");
        $attempt = $prepare->execute(array_values($data));
        $this->clean();
        if ($attempt) {
            return true;
        }
    }

    /**
     * Delete function
     */
    public function delete()
    {
        $prepare = $this->pdo->prepare("DELETE FROM {$this->table} {$this->validateOptional()}");
        $attempt = $prepare->execute();
        $this->clean();
        if ($attempt) {
            return true;
        }
    }

    /**
     * Debug function
     * @return string
     */
    public function debug()
    {
        return "SELECT * FROM {$this->table} {$this->validateOptional()}";
    }
}
