<?php

/*
 * author : sintret@gmail.com
 * simple pdo class
 * sintret.com
 */
 
include 'Db.php';

class Model {

    public $statement;
    public $selectFrom;
    public $where;
    public $limit;
    public $arrayWhere;
    public static $methods = ['where', 'find', 'limit', 'statement'];

    public function __construct()
    {

//        if (!isset(self::$_instance)) {
//            self::$_instance = \sintret\db\Db::instance();
//        }
//
//        return self::$_instance;
    }

    public function find($table)
    {
        $this->selectFrom = "select * from `$table` ";
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
    }

    public function statement()
    {
        return $this->selectFrom . $this->where . $this->limit;
    }

    public function one()
    {
        $this->limit = " LIMIT 1 ";
        //echo $this->statement(); exit(0);
        $query = Db::instance();
        $row = $query->prepare($this->statement());
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
        $query = Db::instance();
        $row = $query->prepare($this->statement());
        if (count($this->arrayWhere)) {
            foreach ($this->arrayWhere as $k => $v) {
                $row->bindParam(":" . $k, $v);
            }
        }
        $row->execute();

        return $row->fetchAll(\PDO::FETCH_OBJ);
    }

}
