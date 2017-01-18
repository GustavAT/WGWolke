<?php
require_once("UserDao.php");
require_once("CommunityDao.php");
require_once("ModuleDao.php");

/*
* Dao Factory provides Dao-Objects for all datatables
*/
class DaoFactory {

    public static function createUserDao() {
        return UserDao::getInstance();
    }

    public static function createCommunityDao() {
        return CommunityDao::getInstance();
    }

    public static function createModuleDao() {
        return ModuleDao::getInstance();
    }
}