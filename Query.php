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
    public $select = ' * ';
    public $setFields;
    public $where;
    public $limit;
    public $arrayWhere;
    public $orderBy;
    public $create;
    public $table;
    public $_property = [];
    public $_relation = [];
    public $isModel = false;
    public $isModelArray = false;

    public function __construct()
    {
        $this->connect = Db::instance();
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
            return $this->_property[$key];
        }
    }

    public function fields($array = [])
    {
        $this->_property = $array;

        return $this;
    }

    public function getColumnNames($table = NULL)
    {
        if (empty($table))
            $table = $this->table;

        $sql = 'DESCRIBE `' . $table . '`';
        $query = $this->connect->prepare($sql);
        $query->execute([':table' => $table]);

        $output = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $output[] = $row['Field'];
        }

        return $output;
    }

    public function generateProperties($table = NULL)
    {
        $columns = $this->getColumnNames($table);
        if ($columns)
            foreach ($columns as $column) {
                $this->_property[$column] = 1;
            }
    }

    public function find($table)
    {
        $this->table = $table;
        $this->selectFrom = 'select ' . $this->select . ' from `' . $table . '`  ';

        $this->generateProperties($table);

        return $this;
    }

    public function select($select)
    {
        $this->select = $select;

        return $this;
    }

    public function where($array = [])
    {
        if (count($array)) {
            $where = ' WHERE ';
            foreach ($array as $k => $v) {
                $where .= ' `' . $k . '` = :' . $k;
            }
            $this->where = $where . ' ';
            $this->arrayWhere = $array;
        }

        return $this;
    }

    private function filterWhere($pattern, $array = [])
    {
        if ($array) {

            $arrayWhere = [$array[1] => $array[2]];
            $where = $this->where;

            $cWhere = empty($where) ? ' WHERE ' : ' ' . $pattern . ' ';

            $resolvedPattern = trim(strtolower($array[0]));
            if ($resolvedPattern == 'in') {
                $qMarks = '';
                if ($array[2])
                    foreach ($array[2] as $k => $v) {

                        $key = $array[1] . $k;
                        $qMarks .= ':' . $key . ',';

                        $keyArray[$key] = $v;
                    }

                $qMarks = substr_replace($qMarks, '', -1);
                $where .= $cWhere . ' `' . $array[1] . '` ' . $array[0] . ' (' . $qMarks . ')';

                $this->arrayWhere = array_merge((array) $this->arrayWhere, (array) $keyArray);
            } else {

                $where .= $cWhere . ' `' . $array[1] . '` ' . $array[0] . ' :' . $array[1];
                $this->arrayWhere = array_merge((array) $this->arrayWhere, (array) $arrayWhere);
            }

            $this->where = $where;
        }
    }

    public function andFilterWhere($array = [])
    {
        $this->filterWhere('AND', $array);

        return $this;
    }

    public function orFilterWhere($array = [])
    {
        $this->filterWhere('OR', $array);

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
        $row->execute($this->arrayWhere);

        $this->isModel = true;

        $this->_property = $row->fetch(\PDO::FETCH_ASSOC);

        return $this;
    }

    public function all()
    {
        $row = $this->connect->prepare($this->statement());
        $row->execute($this->arrayWhere);

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

    public function hasOne($array = [])
    {

        if ($array)
            foreach ($array as $k => $v) {

                $query = new self();

                if (isset($v['find']))
                    $query->find($v['find']);

                if (isset($v['select']))
                    $query->select($v['select']);

                if (isset($v['statement']))
                    $query->statement($v['statement']);

                if (isset($v['where'])) {
                    $where = [];
                    foreach ($v['where'] as $key => $value) {
                        $where[$key] = $this->_property[$value];
                        $where[$key] = $this->$value;
                    }

                    $query->where($where);
                }

                echo "<pre>";
                print_r($query);

                $result = $query->one();

                $this->_relation[$k] = $query->one();
            }

        return $this;
    }

}
