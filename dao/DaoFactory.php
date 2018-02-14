<?php
require_once("UserDao.php");
require_once("CommunityDao.php");
require_once("ModuleDao.php");
require_once("NewsFeedDao.php");
require_once("ToDoListDao.php");
require_once("ToDoEntryDao.php");
require_once("FinanceDao.php");
require_once("DishTagDao.php");
require_once("DishItemDao.php");
require_once("DishItemEntryDao.php");

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

    public static function createToDoListDao() {
        return ToDoListDao::getInstance();
    }

    public static function createToDoEntryDao() {
        return ToDoEntryDao::getInstance();
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

    public static function createDishItemEntryDao() {
        return DishItemEntryDao::getInstance();
    }
}