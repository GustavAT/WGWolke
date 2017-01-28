<?php
session_start();
require_once("../dao/DaoFactory.php");

class SessionHelper {

    public static $session_name_user = "user_oid";
    public static $session_name_last_activity = "last_activity";

    public static function doActivity() {
        if (isset($_SESSION[self::$session_name_last_activity])
            && (time() - $_SESSION[self::$session_name_last_activity] > 1800)) {
            session_unset();
            session_destroy();
            session_start();
        }

       $_SESSION[self::$session_name_last_activity] = time();
    }

    public static function logIn($email, $password) {
        $user_oid = DaoFactory::createUserDao()->checkLogin($email, md5($password));
        if ($user_oid !== null) {
            $_SESSION[self::$session_name_user] = $user_oid;
        }
        return $user_oid !== null;
    }

    public static function logOut() {
        session_unset();
        session_destroy();
        session_start();
    }

    public static function isLoggedIn($user_oid) {
        return isset($_SESSION[self::$session_name_user]);
    }

    public static function getCurrentUserOid() {
        if (isset($_SESSION[self::$session_name_user])) {
            return $_SESSION[self::$session_name_user];
        } else {
            return null;
        }
    }

    public static function setCurrentUserOid($user_oid) {
        $_SESSION[self::$session_name_user] = $user_oid;
    }

    public static function getLastActivity() {
        return isset($_SESSION[self::$session_name_last_activity]) ? $_SESSION[self::$session_name_last_activity] : null;
    }
}