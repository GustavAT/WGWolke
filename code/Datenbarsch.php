<?php
require_once("Sql.php");
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

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "wg_wolke";

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username,
            $this->password, $this->db_name);
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