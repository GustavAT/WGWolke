<?php
require_once("Sql.php");
require_once("Connection.php");
/*
* Database class
* provides functionality for CRUD operations
* wordplay:
* database -> databass -> bass is English for "Barsch" ><((°>
*/
class Datenbarsch {

    private $connection;
    private $mySqliError;
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER,
           DB_PASSWORD, DB_DATABASE); // , $this->port
        $this->mySqliError = mysqli_connect_error();
    }

    public function mySqliError() {
        return $this->mySqliError;
    }

    public function fishQuery($sql, $paramTypes = null, ...$params) {
        $sql_string = get_class($sql) == "Sql" ? $sql->getSql() : $sql;
        $stmt = $this->connection->prepare($sql_string);
        if (isset($paramTypes) && isset($params)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }
}