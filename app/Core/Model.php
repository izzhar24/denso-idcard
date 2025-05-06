<?php

namespace App\Core;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected static $table;
    protected $selects = ['*'];
    protected $wheres = [];
    protected $bindings = [];
    protected $orders = [];

    public static function table()
    {
        return new static;
    }

    public static function getTable()
    {
        return static::$table;
    }

    public function select(array $columns)
    {
        $this->selects = $columns;
        return $this;
    }

    public function where($column, $operator = '=', $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = [$column, strtoupper($direction)];
        return $this;
    }

    protected function buildSql()
    {
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM " . static::getTable();
        $this->bindings = [];

        if (!empty($this->wheres)) {
            $conditions = [];
            foreach ($this->wheres as $where) {
                $conditions[] = "{$where[0]} {$where[1]} ?";
                $this->bindings[] = $where[2];
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        if (!empty($this->orders)) {
            $orderBy = array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orders);
            $sql .= " ORDER BY " . implode(', ', $orderBy);
        }

        return $sql;
    }

    public function get()
    {
        $pdo = Database::getPDO();
        $sql = $this->buildSql();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function paginate($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        $sql = $this->buildSql();
        $sql .= " LIMIT $perPage OFFSET $offset";

        $pdo = Database::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $result = $this->get();
        return $result[0] ?? null;
    }

    public function find($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM " . static::getTable() . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $pdo = Database::getPDO();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO " . static::getTable() . " ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public function update($id, array $data)
    {
        $pdo = Database::getPDO();
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));

        $sql = "UPDATE " . static::getTable() . " SET $setClause WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM " . static::getTable() . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}