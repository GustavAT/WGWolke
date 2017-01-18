<?php
require_once("../dao/DaoFactory.php");

$userDao = DaoFactory::createUserDao();
$user = $userDao->getByCommunity(Util::newEmptyGuid());
foreach ($user as $value) {
    echo $value->toString() . "<br />";
}

$user2 = $userDao->getById("140b4745-dd6c-11e6-805e-704d7b2d09b9");
echo $user2->toString() . "<br />";

// $is_locked = 1;
// $user3 = new User(null, null, "peter.waysocher@gmail.com", md5("1234"), "Peter", "Waysocher", $is_locked, null, Util::newEmptyGuid());
// $userDao->save($user3);

$user2->setFirstName("Andi");
$userDao->save($user2);

// var_dump($user3);
