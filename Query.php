<?php

/*
 * @author : Andy Fitria
 * <sintret@gmail.com>
 * simple pdo class
 * https://sintret.com
 */

include 'Db.php';

class Query {

    private $connect;
    public $statement;
    public $selectFrom;
    public $insertInto;
    public $where;
    public $limit;
    public $arrayWhere;
    public $orderBy;
    public $create;
    public $table;
    public $_property = [];

    public function __construct()
    {
        return $this->connect = Db::instance();
    }

    public function __set($key, $value)
    {
        if (!property_exists($this, $key)) {
            $this->_property[$key] = $value;
        }

        return $this;
    }

    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            return $this->_property->$key;
        }
    }

    public function create($table)
    {
        $this->table = $table;

        return $this;
    }

    public function fields($array = [])
    {
        $this->_property = $array;

        return $this;
    }

    public function find($table)
    {
        $this->table = $table;
        $this->selectFrom = "select * from `$table` ";

        return $this;
    }

    public function where($array = [])
    {
        if (count($array)) {
            $where = ' WHERE ';
            foreach ($array as $k => $v) {
                $where .= $k . '= :' . $k;
            }
            $this->where = $where . ' ';
            $this->arrayWhere = $array;
        }

        return $this;
    }

    public function orderBy($orderBy = NULL)
    {
        if (!empty($orderBy)) {
            $this->orderBy = ' order by ' . $orderBy . ' ';
        }

        return $this;
    }

    public function limit($num)
    {
        $this->limit = " LIMIT " . $num . " ";

        return $this;
    }

    public function statement($statement = NULL)
    {
        if (empty($statement))
            return $this->selectFrom . $this->where . $this->orderBy . $this->limit;
        else
            return $statement;
    }

    public function one()
    {
        $this->limit = " LIMIT 1 ";
        $row = $this->connect->prepare($this->statement());
        if (count($this->arrayWhere)) {
            foreach ($this->arrayWhere as $k => $v) {
                $row->bindParam(":" . $k, $v);
            }
        }
        $row->execute();

        return $row->fetch(\PDO::FETCH_OBJ);
    }

    public function all()
    {
        $row = $this->connect->prepare($this->statement());
        if (count($this->arrayWhere)) {
            foreach ($this->arrayWhere as $k => $v) {
                $row->bindParam(":" . $k, $v);
            }
        }
        $row->execute();

        return $row->fetchAll(\PDO::FETCH_OBJ);
    }

    public function save()
    {
        $statement = 'insert into `' . $this->table . '`  ';

        $statement .= "(" . implode(",", array_keys($this->_property)) . ")";
        $statement .= ' VALUES ';
        $statement .= "(:" . implode(",:", array_keys($this->_property)) . ")";

        $query = $this->connect->prepare($statement);
        $query->execute($this->_property);
        
        $this->_property = $this->find($this->table)->where(['id' => $this->connect->lastInsertId()])->one();

        return $this;
    }

}
