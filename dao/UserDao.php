<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/User.php");

class UserDao extends AbstractDao{
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getAll() {
        $sql = new Sql();
        $sql->select("*");
        $sql->from("user");

        return Datenbarsch::getInstance()->executeQuery($sql);
    }

    public function getById($oid) {
        // ToDo implement
    }

    public function save($user) {
        // ToDo implement
    }

    public function delete($user) {
        // ToDo implement
    }
}