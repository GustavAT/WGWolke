<?php
require_once("UserDao.php");

/*
* Dao Factory provides Dao-Objects for all datatables
*/
class DaoFactory {

    public static function createUserDao() {
        return UserDao::getInstance();
    }

    public static function createCommunityDao() {
        // ToDo implement
    }
}