<?php

require_once("../dao/DaoFactory.php");
require_once("../code/Validator.php");
require_once("../code/Util.php");

$title = Util::parsePost("title");
$user_oid = Util::parsePost("user_oid");
$community_oid = Util::parsePost("community_oid");

$valid = Validator::isValidField($title);

if ($valid) {
    $item = new TodoItem(null, null, $title, false, $community_oid, $user_oid);
    DaoFactory::createTodoItemDao()->save($item);
    echo '{"result": {' .
        '"title": "' . $item->getDescription() .
        '", "dateCreated": "' . $item->getDateCreated() .
        '", "oid": "' . $item->getObjectId() . '"}, "success": true}';
} else {
    echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
}