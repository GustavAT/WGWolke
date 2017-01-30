<?php

require_once("../dao/DaoFactory.php");
require_once("Util.php");

$target_oid = Util::parsePost("target_oid");
$current_oid = Util::parsePost("current_oid");

if ($target_oid && $current_oid) {
    $dao = DaoFactory::createUserDao();
    $current = $dao->getById($current_oid);
    $target = $dao->getById($target_oid);
    if ($current && $target && !$target->isLocked()) {
        $current->setOwner(false);
        $target->setOwner(true);
        $dao->save($current);
        $dao->save($target);
        echo '{"result": "", "success": true}';
    } else {
        echo '{"result": "", "success": false}';
    }    
} else {
    echo '{"result": "", "success": false}';
}
