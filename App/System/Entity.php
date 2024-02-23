<?php

namespace App\System;

#[\AllowDynamicProperties]
class Entity {

    private $DB = '';
    private $childClass = '';

    public $id = '';
    public $columns = [];
    public $table = '';

    // construct
    public function __construct($childClass = '')
    {
        global $DB;

        if (empty($childClass)) {
            $childClass = get_called_class();
        }

        $this->childClass = $childClass;
        $this->table = $this->childClass::$table_name;
        $this->DB = $DB;

        $sql = "SHOW COLUMNS FROM {$this->table};";

        $columns = $this->DB->query($sql);

        foreach ($columns as $column) {
            $columnName = $column['Field'];
            $this->$columnName = '';
            $this->columns[] = $columnName;
        }
    }

    // static classes
    public static function init($resultSet = [])
    {
        $childClassEntity = get_called_class();
        $childClass = new $childClassEntity($childClassEntity);

        foreach ($childClass->columns as $column) {
            $childClass->$column = $resultSet[$column] ?? null;
        }

        return $childClass;
    }

    public static function getAll()
    {
        return (new self(get_called_class()))->getAllInstantiated();
    }

    public static function getAllBy($data = [])
    {
        return (new self(get_called_class()))->getAllByInstantiated($data);
    }

    public static function findBy($data = [], $limit = null)
    {
        return (new self(get_called_class()))->findByInstantiated($data, $limit);
    }

    public static function find(int $id = 0)
    {
        return (new self(get_called_class()))->findInstantiated($id);
    }

    public static function deleteBy(array $data = [])
    {
        return (new self(get_called_class()))->deleteByInstantiated($data);
    }

    public static function query($sql = '', array $parameters = [], $limitOne = null)
    {
        return (new self(get_called_class()))->queryInstantiated($sql, $parameters, $limitOne);
    }

    // private classes
    private function deleteByInstantiated(array $data = [])
    {
        $sql = "delete from {$this->table} where 1";
        $parameters = [];

        foreach ($data as $col => $val) {
            $sql .= " and {$col} = ?";
            $parameters[] = $val;
        }

        return $this->DB->query($sql, $parameters);
    }

    private function getAllInstantiated()
    {
        $sql = "select * from {$this->table} order by id desc;";

        return $this->formatAsChildEntity($this->DB->query($sql));
    }

    private function getAllByInstantiated($data = [])
    {
        $sql = "select * from {$this->table} where 1";
        $parameters = [];
        
        foreach ($data as $col => $val) {
            $sql .= " and {$col} = ?";
            $parameters[] = $val;
        }

        $sql .= " order by id desc;";

        return $this->formatAsChildEntity($this->DB->query($sql, $parameters));
    }

    private function findByInstantiated($data = [], $limit = null)
    {
        $sql = "select * from {$this->table} where 1";
        $parameters = [];
        
        foreach ($data as $col => $val) {
            $sql .= " and {$col} = ?";
            $parameters[] = $val;
        }
        
        $sql .= " order by id desc;";

        $results = $this->DB->query(
            $sql,
            $parameters,
            $limit
        );

        if ($limit == 1) {
            return $this->formatAsChildEntity($results, 1);
        }

        return $this->formatAsChildEntity($results);
    }

    private function findInstantiated(int $id = 0)
    {
        return $this->findByInstantiated(
            ['id' => $id],
            1
        );
    }

    private function queryInstantiated($sql, $parameters = [], $limitOne = null)
    {
        return $this->DB->query($sql, $parameters, $limitOne);
    }

    private function formatAsChildEntity($results, $limitOne = 0)
    {
        if ($limitOne) {
            return $this->childClass::init($results);
        }

        foreach ($results as $key => $result) {
            $results[$key] = $this->childClass::init($result);
        }

        return $results;
    }

    // public functions
    public function create()
    {
        return $this->insert();
    }

    public function insert()
    {
        $columns = [];
        $values = [];

        foreach ($this->columns as $column) {
            // skip over id
            if ($column == 'id') {
                continue;
            }

            if ($column == 'created_at' || $column == 'updated_at') {
                $this->$column = date("Y-m-d H:i:s");
            }

            $columns[] = $column;
            $values[] = $this->$column;
        }

        $this->DB->insert(
            $this->table,
            $columns,
            $values
        );

        $this->id = $this->DB->new_id();

        return true;
    }

    public function save()
    {
        $columns = [];
        $values = [];
        $where = ['id'];
        $whereIs = [$this->id];

        $columns = [];
        $values = [];

        foreach ($this->columns as $column) {
            // skip over id
            if ($column == 'id') {
                continue;
            }

            if ($column == 'updated_at') {
                $value = date("Y-m-d H:i:s");
            } else {
                $value = $this->$column;
            }

            $columns[] = $column;
            $values[] = $value;
        }

        return $this->DB->update(
            $this->table,
            $columns,
            $values,
            $where,
            $whereIs
        );
    }

    public function delete()
    {
        return $this->DB->query("delete from {$this->table} where id = ?", [$this->id]);
    }

    public function clear()
    {
        foreach ($this->columns as $column) {
            $this->$column = null;
        }
    }
}