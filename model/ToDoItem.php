<?php
require_once("Entity.php");

class ToDoItem extends Entity {
    private $description;
    private $is_finished;
    private $community_oid;
    private $user_oid;

    public function __construct($_object_id, $_date_created, $_description, $_is_finished,
            $_community_oid, $_user_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->description = $_description;
        $this->is_finished = $_is_finished;
        $this->community_oid = $_community_oid;
        $this->user_oid = $_user_oid;
    }

    public static function fromRecord($record) {
        return new ToDoItem(
            $record["oid"],
            $record["date_created"],
            $record["description"],
            $record["is_finished"],
            $record["community_oid"],
            $record["user_oid"]
        );
    }

    // getter
    public function getDescription() { return $this->description; }
    public function isFinished() {return $this->is_finished;}
    public function getCommunityOid() {return $this->community_oid;}
    public function getUserOid() {return $this->user_oid;}

    // setter
    public function setDescription($_value) {$this->description = $_value;}
    public function setFinished($_value) {$this->is_finished = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}
    public function setUserOid($_value) {$this->user_oid = $_value;}

    public function toString() {
        return $this->description . ", " . $this->is_finished;
    }
}