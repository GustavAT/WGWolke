<?php

require_once("../dao/DaoFactory.php");
require_once("Validator.php");
require_once("Util.php");
require_once("SessionHelper.php");

$community_name = Util::parsePost("community_name");
$community_description = Util::parsePost("community_description");
$user_first_name = Util::parsePost("user_first_name");
$user_last_name = Util::parsePost("user_last_name");
$user_email = Util::parsePost("user_email");
$user_password = Util::parsePost("user_password");
$user_password_confirm = Util::parsePost("user_password_confirm");

$valid_community = Validator::isValidField($community_name)
    && Validator::smallerThan($community_description, Validator::$description_length);

$valid_user = Validator::isValidField($user_first_name)
    && Validator::isValidField($user_last_name)
    && Validator::isValidEmail($user_email)
    && Validator::smallerThan($user_email, Validator::$email_length)
    && Validator::isValidPassword($user_password)
    && Validator::equals($user_password, $user_password_confirm);

if ($valid_community && $valid_user) {
        $commDao = DaoFactory::createCommunityDao();
        $community = new Community(null, null, $community_name, $community_description);
        $commDao->save($community);

        $user = new User(null, null,
            $user_email,
            md5($user_password),
            $user_first_name,
            $user_last_name,
            false,
            "",
            $community->getObjectId(),
            true);

        Daofactory::createUserDao()->save($user);
        SessionHelper::setCurrentUserOid($user->getObjectId());

        echo '{"result": "", "success": true}';
} else {
    echo '{"result": "", "success": false"}';
}