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
    public $setFields;
    public $where;
    public $limit;
    public $arrayWhere;
    public $orderBy;
    public $create;
    public $table;
    public $_property = [];
    public $isModel = false;
    public $isModelArray = false;

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

    public function setFields()
    {
        $setFields = '';
        if ($this->_property)
            foreach ($this->_property as $k => $v) {
                $setFields .= $k . " = :" . $k . ",";
            }

        $this->setFields = substr_replace($setFields, '', -1);
        ;
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

        $this->isModel = true;

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

        $this->isModelArray = true;

        return $row->fetchAll(\PDO::FETCH_OBJ);
    }

    public function save()
    {
        if ($this->isModel) {

            $this->setFields();

            $this->statement = "UPDATE " . $this->table . " SET " . $this->setFields . $this->where;
        } else {
            
            $statement = 'insert into `' . $this->table . '`  ';
            $statement .= "(" . implode(",", array_keys($this->_property)) . ")";
            $statement .= ' VALUES ';
            $statement .= "(:" . implode(",:", array_keys($this->_property)) . ")";

            $this->statement = $statement;
        }

        $array = array_merge((array) $this->_property, (array) $this->arrayWhere);

        $query = $this->connect->prepare($this->statement);
        $query->execute($array);

        $this->_property = $this->find($this->table)->where(['id' => $this->connect->lastInsertId()])->one();

        return $this;
    }

    public function delete()
    {
        $this->limit = " LIMIT 1 ";
        $this->statement = "DELETE from " . $this->table . $this->where . $this->limit;
        $query = $this->connect->prepare($this->statement);

        return $query->execute($this->arrayWhere);
    }

    public function deleteAll()
    {
        $this->statement = "DELETE from " . $this->table . $this->where;
        $query = $this->connect->prepare($this->statement);

        return $query->execute($this->arrayWhere);
    }

}
