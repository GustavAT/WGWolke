<?php
require_once("UserDao.php");
require_once("CommunityDao.php");
require_once("ModuleDao.php");
require_once("NewsFeedDao.php");
require_once("ToDoItemDao.php");

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

    public static function createNewsFeedDao() {
        return NewsFeedDao::getInstance();
    }

    public static function createToDoItemDao() {
        return ToDoItemDao::getInstance();
    }
}