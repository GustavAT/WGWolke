<?php
require_once("Entity.php");

class ToDoList extends Entity {
    private $community_oid;
    private $list_name;
    private $creator_oid;

    public function __construct($_object_id, $_date_created, $_community_oid,
            $_list_name, $_creator_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->community_oid = $_community_oid;
        $this->list_name = $_list_name;
        $this->creator_oid = $_creator_oid;        
    }

    public static function fromRecord($record) {
        return new ToDoList(
            $record["oid"],
            $record["date_created"],
            $record["community_oid"],
            $record["list_name"],
            $record["creator_oid"]
        );
    }

    // getter
    public function getCommunityOid() {return $this->community_oid;}
    public function getListName() { return $this->list_name; }
    public function getCreatorOid() {return $this->creator_oid;}    

    // setter
    public function setCommunityOid($_value) {$this->community_oid = $_value;}
    public function setListName($_value) {$this->list_name = $_value;}
    public function setCreatorOid($_value) {$this->creator_oid = $_value;}    

    public function toString() {
        return $this->list_name;
    }
}