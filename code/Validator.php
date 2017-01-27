<?php

class Validator {

    public static $description_length = 500;
    public static $name_length = 50;
    public static $email_length = 200;

    public static function isValidField($name) {
        return preg_match("/^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\\/<>?:;|=.,]{1,20}$/", $name)
            && strlen($name) <= self::$name_length;
    }

    public static function smallerThan($content, $length) {
        if (strlen($content) > $length) return false;
        return true;
    }

    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidPassword($password) {
        if (strlen($password) < 8) return false;
        return true;
    }

    public static function equals($item1, $item2) {
        if (!$item1 || !$item2) return false;
        if ($item1 != $item2) return false;
        return true;
    }

}