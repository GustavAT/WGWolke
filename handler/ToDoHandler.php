<?php

require_once("../dao/DaoFactory.php");
require_once("../code/Validator.php");
require_once("../code/Util.php");

$ids = Util::parsePost("ids");
$mode = Util::parsePost("mode");
$list_oid = Util::parsePost("list_oid");
$user_oid = Util::parsePost("user_oid");
$description = Util::parsePost("description");

$valid = Validator::isValidField($description);
$todo_ids = explode(";", $ids);

$entry_dao = DaoFactory::createToDoEntryDao();

if ($mode == 1) {
    if ($todo_ids) {
        foreach ($todo_ids as $todo_id) {
            $todo = $entry_dao->getById($todo_id);
            if ($todo !== null) {
                $todo->setFinished(true);
                $entry_dao->save($todo);            
            }
        }
        echo '{"result": "", "success": true}';
    } else {
        echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
    }
} else if ($mode == 2) {
    if ($list_oid != null && $user_oid != null && $valid) {
        $item = new ToDoEntry(null, null, $list_oid, $description, 0, $user_oid);
        $entry_dao->save($item);
        echo '{"result": {' .
                '"title" : "' . $item->getDescription() . '",' .
                '"dateCreated" : "' . $item->getDateCreated() . '",' .
                '"oid" : "' . $item->getObjectId() . '"' .
            '}, "success": true}';
    } else {
        echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
    }    
} else {
    echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
}