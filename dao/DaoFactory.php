<?php
require_once("UserDao.php");
require_once("CommunityDao.php");
require_once("ModuleDao.php");
require_once("NewsFeedDao.php");
require_once("ToDoItemDao.php");
require_once("FinanceDao.php");
require_once("DishTagDao.php");
require_once("DishItemDao.php");

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

    public static function createFinanceDao() {
        return FinanceDao::getInstance();
    }

    public static function createDishTagDao() {
        return DishTagDao::getInstance();
    }

    public static function createDishItemDao() {
        return DishItemDao::getInstance();
    }
}