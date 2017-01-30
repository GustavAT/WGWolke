<?php

require_once("../dao/DaoFactory.php");
require_once("../code/Validator.php");
require_once("../code/Util.php");

$ids = Util::parsePost("ids");

$todo_ids = explode(";", $ids);

if ($todo_ids) {
    foreach ($todo_ids as $todo_id) {
        $todo = DaoFactory::createTodoItemDao()->getById($todo_id);
        if ($todo !== null) {
            $todo->setFinished(true);
            DaoFactory::createTodoItemDao()->save($todo);            
        }
    }
    echo '{"result": "", "success": true}';
} else {
    echo '{"result": "' . Resources::$unknown_error . '", "success": false}';
}