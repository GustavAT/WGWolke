<?php
require_once("Entity.php");

class ToDoEntry extends Entity {
    private $todo_list_oid;
    private $description;
    private $is_finished;
    private $user_oid;

    public function __construct($_object_id, $_date_created, $_todo_list_oid,
            $_description, $_is_finished, $_user_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->todo_list_oid = $_todo_list_oid;
        $this->description = $_description;
        $this->is_finished = $_is_finished;
        $this->user_oid = $_user_oid;
    }

    public static function fromRecord($record) {
        return new ToDoEntry(
            $record["oid"],
            $record["date_created"],
            $record["todo_list_oid"],
            $record["description"],
            $record["is_finished"],
            $record["user_oid"]
        );
    }

    // getter
    public function getTodoListOid() { return $this->todo_list_oid; }
    public function getDescription() { return $this->description; }
    public function isFinished() {return $this->is_finished;}
    public function getUserOid() {return $this->user_oid;}

    // setter
    public function setTodoListOid($_value) {$this->todo_list_oid = $_value;}
    public function setDescription($_value) {$this->description = $_value;}    
    public function setFinished($_value) {$this->is_finished = $_value;}
    public function setUserOid($_value) {$this->user_oid = $_value;}

    public function toString() {
        return $this->description . ", " . $this->is_finished;
    }
}