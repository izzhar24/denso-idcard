<?php
namespace App\Core;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected static $table;
    protected $selects = ['*'];
    protected $wheres = [];

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
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function get()
    {
        $pdo = Database::getPDO();
        $sql = "SELECT " . implode(', ', $this->selects) . " FROM " . static::getTable();

        $bindings = [];

        if (!empty($this->wheres)) {
            $whereSql = array_map(function ($where) {
                return "{$where[0]} {$where[1]} ?";
            }, $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $whereSql);

            $bindings = array_map(fn($w) => $w[2], $this->wheres);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }
}
