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
    protected $limit;
    protected $offset;
    protected $with = [];


    public static function table()
    {
        return new static;
    }

    public static function getTable()
    {
        return static::$table;
    }

    public function getPrimaryKey()
    {
        return static::$primaryKey ?? 'id';
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

    public function whereLike($column, $value)
    {
        $this->wheres[] = [$column, 'LIKE', "%{$value}%"];
        return $this;
    }

    public static function exists($column, $value)
    {
        $table = static::$table; // Pastikan properti static $table didefinisikan di masing-masing model
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchColumn() > 0;
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

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function count()
    {
        $pdo = Database::getPDO();
        $sql = "SELECT COUNT(*) as count FROM " . static::getTable();
        return $pdo->query($sql)->fetch()['count'];
    }

    public function with($relations)
    {
        $relations = is_array($relations) ? $relations : [$relations];
        foreach ($relations as $relation) {
            $this->parseWithRelation($relation, $this->with);
        }
        return $this;
    }

    protected function parseWithRelation($relation, &$with)
    {
        if (str_contains($relation, '.')) {
            $parts = explode('.', $relation);
            $first = array_shift($parts);
            if (!isset($with[$first])) {
                $with[$first] = [];
            }
            $this->parseWithRelation(implode('.', $parts), $with[$first]);
        } else {
            if (!isset($with[$relation])) {
                $with[$relation] = [];
            }
        }
    }
    protected function loadRelation(&$row, $relation)
    {
        if (strpos($relation, '.') !== false) {
            [$first, $nested] = explode('.', $relation, 2);

            if (method_exists($this, $first)) {
                $related = $this->$first->call($row); // atau $this->$first() jika tidak butuh argumen
                if ($related) {
                    // Buat instance model dari class terkait
                    $relatedModel = new ($related::class);
                    $relatedWith = [$nested];
                    $relatedRow = $relatedModel->with($relatedWith)->where('id', $related->id)->first(); // atau langsung fetch by ID
                    $row->$first = $relatedRow;
                }
            }
        } else {
            if (method_exists($this, $relation)) {
                $row->$relation = $this->$relation($row);
            }
        }
    }

    public function has($relation)
    {
        $this->with[] = $relation;
        return $this;
    }
    protected function belongsTo($relatedClass, $foreignKey, $ownerKey = 'id')
    {
        $foreignValue = $this->{$foreignKey} ?? null;
        if (!$foreignValue) return null;

        return $relatedClass::table()
            ->where($ownerKey, $foreignValue)
            ->first();
    }

    protected function hasOne($relatedClass, $foreignKey, $localKey = 'id')
    {
        $localValue = $this->$localKey ?? null;
        if (!$localValue) return null;

        return $relatedClass::table()
            ->where($foreignKey, $localValue)
            ->first();
    }

    protected function hasMany($relatedClass, $foreignKey, $localKey = 'id')
    {
        $localValue = $this->$localKey ?? null;
        if (!$localValue) return [];

        return $relatedClass::table()
            ->where($foreignKey, $localValue)
            ->get();
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

        if ($this->limit !== null) {
            $sql .= " LIMIT " . intval($this->limit);
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET " . intval($this->offset);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($bindings);
        $results = $stmt->fetchAll();

        // Eager load relations
        if (!empty($this->with)) {
            foreach ($results as &$row) {
                foreach ($this->with as $relation => $nested) {
                    if (is_int($relation)) {
                        $relation = $nested;
                        $nested = [];
                    }
                    $this->loadRelation($row, [$relation => $nested]);
                }
            }
        }


        return $results;
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
