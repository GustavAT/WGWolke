<?php
require_one("./AbstractDao.php");

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
        // ToDo implement
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