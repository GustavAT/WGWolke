<?php

require_once("../dao/DaoFactory.php");
require_once("Validator.php");
require_once("Util.php");
require_once("SessionHelper.php");
require_once("Resources.php");

$community_oid = Util::parsePost("community_oid");
$first_name = Util::parsePost("first_name");
$last_name = Util::parsePost("last_name");
$email = Util::parsePost("email");
$password = Util::parsePost("password");
$password_confirm = Util::parsePost("password_confirm");

$valid_user = Validator::isValidField($first_name)
    && Validator::isValidField($last_name)
    && Validator::isValidEmail($email)
    && Validator::smallerThan($email, Validator::$email_length)
    && Validator::isValidPassword($password)
    && Validator::equals($password, $password_confirm);

$email_unique = Validator::isEmailUnique($email);


if ($valid_user && $email_unique) {

        $user = new User(null, null,
            $email,
            md5($password),
            $first_name,
            $last_name,
            false,
            "",
            $community_oid,
            false);

        Daofactory::createUserDao()->save($user);

        echo '{"result": "", "success": true}';
} else if (!$email_unique) {
    echo '{"result": "' . Resources::$duplicate_email . '", "success": false}';
} else {
    echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
}