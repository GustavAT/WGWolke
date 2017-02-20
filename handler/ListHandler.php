<?php

require_once("../dao/DaoFactory.php");
require_once("../code/Validator.php");
require_once("../code/Util.php");

$mode = Util::parsePost("mode");
$name = Util::parsePost("name");
$list_oid = Util::parsePost("list_oid");
$user_oid = Util::parsePost("user_oid");
$community_oid = Util::parsePost("community_oid");
$member_oids_raw = Util::parsePost("member_oids");

if ($mode == 1) {
    // create list
    $valid = Validator::isValidField($name);
    if ($valid) {
        $list = new ToDoList(null, null, $community_oid, $name, $user_oid);
        DaoFactory::createToDoListDao()->save($list);
        echo '{"result": "' . $list->getObjectId() . '", "success": true}';
    } else {
        echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
    }
} else if ($mode == 2) {
    // update member list
    $list_dao = DaoFactory::createToDoListDao();
    $existing_oids = $list_dao->getMemberOids($list_oid);
    $member_oids = explode(",", $member_oids_raw);

    foreach ($member_oids as $value) {
        if (!in_array($value, $existing_oids)) {
            $list_dao->addMember($list_oid, $value);
        }
    }

    foreach ($existing_oids as $value) {
        if (!in_array($value, $member_oids)) {
            $list_dao->removeMember($list_oid, $value);
        }
    }


} else if ($mode == 3) {
    // delete list
    $list_dao = DaoFactory::createToDoListDao();
    $target_list = $list_dao->getById($list_oid);
    if ($target_list !== null && $target_list->getCreatorOid() == $user_oid) {
        $list_dao->delete($target_list);
        $list_dao->removeMember($target_list->getObjectId());
    } else {
        echo '{"result": "' . Resources::$permission_denied . '", "success": false}';
    }
} else {
    echo '{"result": "' . Resources::$not_supported . '", "success": false}';
}