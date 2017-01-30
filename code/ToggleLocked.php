<?php

require_once("../dao/DaoFactory.php");
require_once("Util.php");

$user_oid = Util::parsePost("user_oid");

if ($user_oid) {
    $user = DaoFactory::createUserDao()->getById($user_oid);
    if ($user) {
        $user->setLocked(!$user->isLocked());
        DaoFactory::createUserDao()->save($user);        
        echo '{"result": "", "success": true}';
    } else {
        echo '{"result": "", "success": false}';
    }
} else {
    echo '{"result": "", "success": false}';
}