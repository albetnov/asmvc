<?php

namespace App\Asmvc\Core\Database;

use App\Asmvc\Core\Exceptions\CallingToUndefinedMethod;

class Database
{
    /**
     * Define require variables
     */
    private $table, $data, $tableDefined = false;
    private $whereStmt = null, $orderStmt, $limitStmt, $joinStmt, $whereNoFormat = false;
    private $pdo;
    private static $last_insert_id;

    public function __call($method, $arguments)
    {
        if (!method_exists($this, $method)) {
            throw new CallingToUndefinedMethod($method);
        }

        if (is_array($arguments)) {
            return $this->$method(...$arguments);
        } else {
            return $this->$method($arguments);
        }
    }

    public static function __callStatic($method, $arguments)
    {
        if (!method_exists(self::class, $method)) {
            throw new CallingToUndefinedMethod($method);
        }

        if (is_array($arguments)) {
            return (new self)->$method(...$arguments);
        } else {
            return (new self)->$method($arguments);
        }
    }

    /**
     * Defining your table
     * @param string $table
     */
    public function defineTable(string $table): void
    {
        $this->table = $table;
        $this->tableDefined = true;
    }

    /**
     * Initiating Connection
     */
    public function __construct()
    {
        $env = provider_config()['model'];
        if ($env != 'asmvc') {
            throw new ModelDriverException();
        }
        $this->pdo = (new Connection)->getConnection();
    }

    /**
     * Format the field to be used.
     */
    private function processField(): string
    {
        return implode(',', array_keys($this->data));
    }

    /**
     * Count required prepare statement
     */
    private function totalPrepare(): string
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
    public function table(string $name): self
    {
        $this->table = $name;
        return $this;
    }

    /**
     * Where function
     * @param string $field
     * @param string value
     * @param string $operator
     * @return self
     */
    public function where(string $field, string $value, ?string $operator = null): self
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
    public function whereNoFormat(): self
    {
        if ($this->whereStmt) {
            throw new QueryBuilderException("whereNoFormat", "Please use whereNoFormat before where query.");
        }
        $this->whereNoFormat = true;
        return $this;
    }

    /**
     * orWhere function
     * @param string $field
     * @param string value
     * @param string $operator
     * @return self
     */
    public function orWhere(string $field, string $value, ?string $operator = null): self
    {
        if (!$this->whereStmt) {
            throw new QueryBuilderException("orWhere", "Please use orWhere() after where()");
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
     * @param string $column
     * @param string $order
     * @return self
     */
    public function orderBy(string $column, string $order): self
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
     * Order by DESC.
     */
    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, "DESC");
    }

    /**
     * Order by ASC.
     */
    public function orderByAsc(string $column): self
    {
        return $this->orderBy($column, "ASC");
    }

    /**
     * Limit function
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        noSelfChained($this->limitStmt, 'limit');
        $string = "LIMIT {$limit}";
        $this->limitStmt = $string;
        return $this;
    }

    private function joinHandler(string $opening, string $table, string $from_id, string $to_id): self
    {
        $string = "";
        if ($this->joinStmt) {
            $string = " ";
        }
        $string .= "{$opening} {$table} ON {$from_id} = {$to_id}";
        $this->joinStmt .= $string;
        return $this;
    }

    /**
     * Perform Inner Join Function
     *
     *  @param string $table
     * @param string $from_id
     * @param string $to_id
     * @return self
     */
    public function join(string $table, string $from_id, string $to_id): self
    {
        return $this->joinHandler("INNER JOIN", $table, $from_id, $to_id);
    }

    /**
     * Perform Left Join Function
     *
     *  @param string $table
     * @param string $from_id
     * @param string $to_id
     * @return self
     */
    public function leftJoin(string $table, string $from_id, string $to_id): self
    {
        return $this->joinHandler("LEFT JOIN", $table, $from_id, $to_id);
    }

    /**
     * Perform Right Join Function
     *
     *  @param string $table
     * @param string $from_id
     * @param string $to_id
     * @return self
     */
    public function rightJoin(string $table, string $from_id, string $to_id): self
    {
        return $this->joinHandler("RIGHT JOIN", $table, $from_id, $to_id);
    }

    /**
     * Perform Full Join Function
     *
     *  @param string $table
     * @param string $from_id
     * @param string $to_id
     * @return self
     */
    public function fullJoin(string $table, string $from_id, string $to_id): self
    {
        return $this->joinHandler("FULL JOIN", $table, $from_id, $to_id);
    }

    /**
     * Run and format every possible query statement in use
     * @return string
     */
    private function validateOptional(): string
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
    private function clean(): void
    {
        if (!$this->tableDefined) {
            $this->table = '';
            $this->data = '';
            $this->whereStmt = null;
            $this->orderStmt = null;
            $this->limitStmt = null;
            $this->joinStmt = null;
        }
    }

    /**
     * Fetching data
     * @param array $fields
     * @return \PDO
     */
    public function get($fields = []): array | bool
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
     * @return \PDO
     */
    public function first($fields = []): mixed
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
     * @return \PDO
     */
    public function count(): mixed
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
     * @param array $data
     * @param boolean $last_insert_id
     * @return boolean
     */
    public function insert(array $data, bool $last_insert_id = false): bool
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
        return false;
    }

    /**
     * Get Last Insert Id
     * @return string|int
     */
    public function lastInsertId(): string | int
    {
        return self::$last_insert_id;
    }

    /**
     * Update function
     * @param array $data
     * @return boolean
     */
    public function update(array $data): bool
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
        return false;
    }

    /**
     * Delete function
     * @return boolean
     */
    public function delete(): bool
    {
        $prepare = $this->pdo->prepare("DELETE FROM {$this->table} {$this->validateOptional()}");
        $attempt = $prepare->execute();
        $this->clean();
        if ($attempt) {
            return true;
        }
        return false;
    }

    /**
     * Debug function
     * @return string
     */
    public function debug(): string
    {
        return "SELECT * FROM {$this->table} {$this->validateOptional()}";
    }
}
