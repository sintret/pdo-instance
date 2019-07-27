<?php

/*
 * @author : Andy Fitria
 * <sintret@gmail.com>
 * simple pdo class
 * https://sintret.com
 */

class Db {

    /**
     * singleton instance
     *
     * @var PDOConnection
     */
    protected static $_instance = null;
    /**
     * Returns singleton instance
     *
     */
    public static function instance()
    {

        if (!isset(self::$_instance)) {

            $conn = null;
            try {

                include dirname(__FILE__) . '\config.php';                
                $conn = new \PDO("mysql:host=$host;dbname=$db", $username, $password);

                //Set common attributes
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                return $conn;
            } catch (PDOException $e) {

                //TODO: flag to disable errors?
                echo $e->getMessage() . $e->getLine();
                throw $e;
            } catch (Exception $e) {

                //TODO: flag to disable errors?
                echo $e->getMessage() . $e->getLine();
                throw $e;
            }
        }

        return self::$_instance;
    }

    /**
     * Hide constructor, protected so only subclasses and self can use
     */
    protected function __construct()
    {

    }

    function __destruct()
    {

    }

    /** PHP seems to need these stubbed to ensure true singleton * */
    public function __clone()
    {
        return false;
    }

    public function __wakeup()
    {
        return false;
    }

}
